<?php
namespace Tests\Feature;
use Tests\TestCase;
use App\Models\User;
use App\Services\Acceso;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
class AccesoTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->assertSame('sqlite',config('database.default'));
        $this->assertSame(':memory:',config('database.connections.sqlite.database'));
        DB::unprepared(file_get_contents(__DIR__.'/../Fixtures/schema.sql'));
        DB::unprepared(file_get_contents(__DIR__.'/../Fixtures/catalogos.sql'));
        foreach(Acceso::ESENCIALES as $clave) $this->permiso(1,$clave);
    }
    private function permiso(int $rol,string $clave,bool $activo=true): void
    {
        $id=DB::table('permisos')->where('clave',$clave)->value('id_permiso');
        DB::table('rol_permiso')->updateOrInsert(['id_rol'=>$rol,'id_permiso'=>$id],['activo'=>$activo,'created_at'=>now(),'updated_at'=>now()]);
    }
    private function usuario(int $rol=1,string $estado='activo'): User
    {
        $user=new User(['name'=>'Usuario Prueba','email'=>uniqid().'@example.test','password'=>Hash::make('SeguraPrueba123!')]); $user->id_rol=$rol; $user->estado=$estado; $user->save(); return $user;
    }
    private function equipo(): void
    {
        DB::table('personas')->insert(['id_persona'=>1,'nombre'=>'Cliente','apellidos'=>'Prueba','telefono'=>'0000000000']);
        DB::table('clientes')->insert(['id_cliente'=>1,'id_persona'=>1]);
        DB::table('maquinas')->insert(['id_maquina'=>1,'codigo'=>'TEST-01','id_tipo_maquina'=>1,'id_estado_maquina'=>1,'fecha_ingreso'=>'2026-01-01']);
        DB::table('detalle_tarifa')->insert(['id_tarifa'=>1,'id_tipo_maquina'=>1,'id_periodo'=>1,'precio'=>100,'deposito'=>50,'fecha_inicio'=>'2026-01-01']);
    }
    private function renta(): void
    {
        $this->equipo();
        DB::table('rentas')->insert(['id_renta'=>1,'id_cliente'=>1,'id_estado_renta'=>2,'fecha_salida'=>'2026-01-02 10:00:00','fecha_devolucion_programada'=>'2026-01-03 10:00:00']);
        DB::table('detalle_rentas')->insert(['id_detalle_renta'=>1,'id_renta'=>1,'id_maquina'=>1,'id_tarifa'=>1,'cantidad_periodos'=>1,'precio_aplicado'=>100]);
        DB::table('maquinas')->where('id_maquina',1)->update(['id_estado_maquina'=>2]);
    }
    public function test_registro_ignora_campos_de_privilegio(): void
    {
        $this->post('/registro',['name'=>'Persona Prueba','email'=>'registro@example.test','password'=>'SeguraPrueba123!','password_confirmation'=>'SeguraPrueba123!','estado'=>'activo','id_rol'=>1])->assertRedirect('/login')->assertSessionHas('success');
        $user=User::where('email','registro@example.test')->firstOrFail(); $this->assertSame('pendiente',$user->estado); $this->assertNull($user->id_rol); $this->assertTrue(Hash::check('SeguraPrueba123!',$user->password));
    }
    public function test_login_bloquea_estados_no_aprobados_y_roles_inactivos(): void
    {
        foreach(['pendiente','inactivo','rechazado'] as $estado) { $user=$this->usuario(2,$estado); $this->post('/login',['email'=>$user->email,'password'=>'SeguraPrueba123!'])->assertSessionHasErrors('email'); $this->assertGuest(); }
        $user=$this->usuario(2); DB::table('roles')->where('id_rol',2)->update(['activo'=>0]); $this->post('/login',['email'=>$user->email,'password'=>'SeguraPrueba123!'])->assertSessionHasErrors('email'); $this->assertGuest();
    }
    public function test_login_regenera_y_dirige_a_cada_panel(): void
    {
        foreach([1=>'superadmin',2=>'rentas',3=>'mantenimiento',4=>'administrativo'] as $id=>$clave) {
            $user=$this->usuario($id); $this->post('/login',['email'=>$user->email,'password'=>'SeguraPrueba123!'])->assertRedirect(route(Acceso::INICIOS[$clave]));
            $this->get(route(Acceso::INICIOS[$clave]))->assertOk(); $this->post('/logout')->assertRedirect('/login');
        }
    }
    public function test_sin_rol_o_panel_ajeno_se_bloquea(): void
    {
        $user=$this->usuario(2); $this->actingAs($user)->get('/inicio/administrativo')->assertForbidden();
        DB::table('users')->where('id',$user->id)->update(['id_rol'=>null]); $this->get('/panel')->assertForbidden();
    }
    public function test_aprobacion_exige_rol_activo_y_permiso(): void
    {
        $admin=$this->usuario(); $pending=$this->usuario(2,'pendiente');
        $this->actingAs($admin)->patch('/accesos/usuarios/'.$pending->id,['operacion'=>'aprobar'])->assertSessionHasErrors('id_rol');
        $this->patch('/accesos/usuarios/'.$pending->id,['operacion'=>'aprobar','id_rol'=>2])->assertRedirect();
        $this->assertSame('activo',$pending->fresh()->estado); $this->assertSame(2,$pending->fresh()->id_rol);
        $this->actingAs($pending)->patch('/accesos/usuarios/'.$admin->id,['operacion'=>'desactivar'])->assertForbidden();
    }
    public function test_permiso_revocado_se_aplica_en_la_siguiente_peticion(): void
    {
        $user=$this->usuario(2); $this->actingAs($user)->get('/clientes')->assertForbidden(); $this->permiso(2,'clientes.consultar'); $this->get('/clientes')->assertOk();
        $this->permiso(2,'clientes.consultar',false); $this->get('/clientes')->assertForbidden();
    }
    public function test_cuenta_y_rol_desactivados_invalidan_sesion(): void
    {
        $user=$this->usuario(2); $this->actingAs($user)->get('/panel')->assertRedirect(); DB::table('users')->where('id',$user->id)->update(['estado'=>'inactivo']); $this->get('/panel')->assertForbidden();
        DB::table('users')->where('id',$user->id)->update(['estado'=>'activo']); DB::table('roles')->where('id_rol',2)->update(['activo'=>0]); $this->get('/panel')->assertForbidden();
    }
    public function test_cambio_de_rol_en_sesion_respeta_configuracion_actual(): void
    {
        $user=$this->usuario(2); $this->permiso(2,'clientes.consultar'); $this->actingAs($user)->get('/clientes')->assertOk();
        DB::table('users')->where('id',$user->id)->update(['id_rol'=>3]); $this->get('/clientes')->assertForbidden(); $this->get('/panel')->assertRedirect(route('mantenimiento.inicio'));
    }
    public function test_ultimo_superadmin_no_se_desactiva_degrada_ni_pierde_permisos(): void
    {
        $admin=$this->usuario(); $this->actingAs($admin);
        $this->patch('/accesos/usuarios/'.$admin->id,['operacion'=>'desactivar'])->assertSessionHasErrors('continuidad');
        $this->patch('/accesos/usuarios/'.$admin->id,['operacion'=>'rol','id_rol'=>2])->assertSessionHasErrors('continuidad');
        $this->patch('/accesos/roles/1',['activo'=>0])->assertSessionHasErrors('continuidad');
        $this->put('/accesos/permisos/1',['permisos'=>[]])->assertSessionHasErrors('continuidad');
        $this->assertSame('activo',$admin->fresh()->estado); $this->assertSame(1,$admin->fresh()->id_rol);
    }
    public function test_segundo_superadmin_permite_desactivar_el_primero(): void
    {
        $admin=$this->usuario(); $otro=$this->usuario(); $this->actingAs($admin)->patch('/accesos/usuarios/'.$otro->id,['operacion'=>'desactivar'])->assertRedirect(); $this->assertSame('inactivo',$otro->fresh()->estado);
    }
    public function test_matriz_no_concede_seguridad_a_roles_de_negocio(): void
    {
        $this->actingAs($this->usuario())->put('/accesos/permisos/2',['permisos'=>[1]])->assertUnprocessable();
        $this->put('/accesos/permisos/2',['permisos'=>[9999]])->assertSessionHasErrors('permisos.0');
        $this->put('/accesos/permisos/2',['permisos'=>[16]])->assertUnprocessable();
        $this->put('/accesos/permisos/2',['permisos'=>[12]])->assertRedirect()->assertSessionHas('success');
    }
    public function test_matriz_y_pantallas_de_seguridad(): void
    {
        $this->actingAs($this->usuario()); foreach(['/accesos/usuarios','/accesos/roles','/accesos/permisos'] as $url) $this->get($url)->assertOk();
        $this->put('/accesos/permisos/3',['permisos'=>[9]])->assertRedirect();
        $this->actingAs($this->usuario(3))->get('/clientes')->assertOk();
    }
    public function test_superadmin_no_elude_permisos_de_negocio(): void
    {
        $this->actingAs($this->usuario())->get('/clientes')->assertForbidden();
    }
    public function test_catalogos_solo_de_modulos_autorizados(): void
    {
        $user=$this->usuario(3); $this->permiso(3,'catalogos.consultar'); $this->permiso(3,'mantenimientos.consultar');
        $this->actingAs($user)->get('/tipos-mantenimiento')->assertOk(); $this->get('/metodos_pago')->assertForbidden(); $this->get('/periodos_renta')->assertForbidden();
        $this->get('/inicio/mantenimiento')->assertOk()->assertDontSee('Cuentas pendiente')->assertDontSee('Pagos');
    }
    public function test_modificar_no_permite_devolver_ni_cambiar_estado(): void
    {
        $this->renta(); $user=$this->usuario(2); $this->permiso(2,'rentas.modificar'); $this->permiso(2,'maquinas.modificar'); $this->actingAs($user);
        $this->patch('/rentas/1/devolver',['detalles'=>[1],'fecha_devolucion_real'=>'2026-01-03 10:00:00'])->assertForbidden();
        $this->patch('/maquinas/1/estado',['id_estado_maquina'=>1])->assertForbidden();
        $this->put('/rentas/1',['fecha_devolucion_programada'=>'2026-01-03','fecha_devolucion_real'=>'2026-01-03'])->assertSessionHasErrors('fecha_devolucion_real');
        $this->put('/maquinas/1',['codigo'=>'TEST-01','id_tipo_maquina'=>1,'id_estado_maquina'=>1,'marca'=>'Prueba','fecha_ingreso'=>'2026-01-01'])->assertForbidden();
    }
    public function test_equipo_rentado_no_puede_marcarse_disponible(): void
    {
        $this->renta(); $user=$this->usuario(3); $this->permiso(3,'maquinas.cambiar_estado');
        $this->actingAs($user)->patch('/maquinas/1/estado',['id_estado_maquina'=>1])->assertSessionHasErrors('id_estado_maquina');
        $this->assertSame(2,DB::table('maquinas')->value('id_estado_maquina'));
    }
    public function test_devolucion_actualiza_detalle_renta_y_maquina(): void
    {
        $this->renta(); $user=$this->usuario(2); $this->permiso(2,'rentas.devolver'); $this->actingAs($user)->get('/rentas/1/devolucion')->assertOk();
        $this->patch('/rentas/1/devolver',['detalles'=>[1],'fecha_devolucion_real'=>'2026-01-03 10:00:00'])->assertRedirect();
        $this->assertNotNull(DB::table('detalle_rentas')->value('fecha_devolucion_real')); $this->assertSame(3,DB::table('rentas')->value('id_estado_renta')); $this->assertSame(1,DB::table('maquinas')->value('id_estado_maquina'));
    }
    public function test_persona_compartida_no_se_modifica_fuera_de_contexto(): void
    {
        $this->equipo(); DB::table('proveedores')->insert(['id_proveedor'=>1,'id_persona'=>1]); $this->permiso(2,'clientes.modificar'); $this->actingAs($this->usuario(2));
        $this->put('/clientes/1',['id_persona'=>1,'persona'=>['nombre'=>'Otro','apellidos'=>'Cliente','telefono'=>'123']])->assertSessionHasErrors('id_persona');
        $this->put('/clientes/1',['persona'=>['nombre'=>'Otro','apellidos'=>'Cliente','telefono'=>'123']])->assertRedirect();
        $this->assertSame('Cliente',DB::table('personas')->where('id_persona',1)->value('nombre')); $this->assertNotEquals(1,DB::table('clientes')->value('id_persona'));
        $this->put('/personas/1',['nombre'=>'Atacante'])->assertForbidden();
    }
    public function test_renta_registra_detalles_con_importes_del_servidor(): void
    {
        $this->equipo(); $this->permiso(2,'rentas.registrar'); $this->actingAs($this->usuario(2))->post('/rentas',['id_cliente'=>1,'fecha_salida'=>'2026-01-02 10:00:00','fecha_devolucion_programada'=>'2026-01-03 10:00:00','detalles'=>[['id_maquina'=>1,'id_tarifa'=>1,'cantidad_periodos'=>1]]])->assertRedirect();
        $this->assertEquals(100,DB::table('detalle_rentas')->value('precio_aplicado')); $this->assertSame(2,DB::table('maquinas')->value('id_estado_maquina'));
    }
    public function test_eliminacion_exige_permiso_y_registro_existente(): void
    {
        $user=$this->usuario(2); $this->actingAs($user)->delete('/clientes/1')->assertForbidden();
        $this->permiso(2,'clientes.eliminar'); $this->delete('/clientes/1')->assertNotFound();
    }
    public function test_listados_y_formularios_existentes_se_integran(): void
    {
        $this->renta(); $user=$this->usuario(4);
        foreach(DB::table('permisos')->whereNotIn('modulo',['usuarios','roles','permisos'])->where('accion','!=','eliminar')->pluck('clave') as $clave) $this->permiso(4,$clave);
        $this->actingAs($user);
        foreach(['clientes','proveedores','maquinas','compras','detalle_tarifa','periodos_renta','mantenimientos','tipos-mantenimiento','bajas-maquinas','motivos-baja','pagos','metodos_pago','estados_renta','estados_maquina','tipos_maquina','direcciones_clientes','rentas'] as $url) {
            $this->get('/'.$url)->assertOk(); $this->get('/'.$url.'/create')->assertOk();
        }
        foreach(['/clientes/1/edit','/maquinas/1/edit','/rentas/1/edit','/rentas/1','/maquinas/1/estado','/pagos/renta/1'] as $url) $this->get($url)->assertOk();
    }
    public function test_operacion_sin_permiso_de_consulta_tiene_navegacion(): void
    {
        $this->equipo(); $user=$this->usuario(3); $this->permiso(3,'maquinas.cambiar_estado'); $this->actingAs($user);
        $this->get('/inicio/mantenimiento')->assertOk()->assertSee('Maquinaria');
        $this->get('/maquinas')->assertOk()->assertSee('Identificador')->assertDontSee('TEST-01');
        $this->get('/operaciones/abrir?modulo=maquinas&operacion=cambiar_estado&id=1')->assertRedirect(route('maquinas.estado_formulario',1));
        $this->get('/maquinas/1/estado')->assertOk();
    }
    public function test_mantenimiento_y_baja_respetan_ocupacion_y_transacciones(): void
    {
        $this->renta(); $user=$this->usuario(3); $this->permiso(3,'mantenimientos.registrar'); $this->permiso(3,'bajas_maquinas.registrar'); $this->actingAs($user);
        $this->post('/mantenimientos',['id_maquina'=>1,'id_tipo_mantenimiento'=>1,'fecha_entrada'=>'2026-01-03','descripcion'=>'Prueba'])->assertSessionHasErrors('operacion');
        $this->post('/bajas-maquinas',['id_maquina'=>1,'id_motivo_baja'=>1,'fecha_baja'=>'2026-01-03'])->assertUnprocessable();
        $this->assertSame(0,DB::table('mantenimientos')->count()); $this->assertSame(0,DB::table('bajas_maquinas')->count());
        DB::table('detalle_rentas')->update(['fecha_devolucion_real'=>'2026-01-03']); DB::table('rentas')->update(['id_estado_renta'=>3]); DB::table('maquinas')->update(['id_estado_maquina'=>1]);
        $this->post('/mantenimientos',['id_maquina'=>1,'id_tipo_mantenimiento'=>1,'fecha_entrada'=>'2026-01-03','descripcion'=>'Prueba'])->assertRedirect();
        $this->assertSame(3,DB::table('maquinas')->value('id_estado_maquina'));
        $this->post('/bajas-maquinas',['id_maquina'=>1,'id_motivo_baja'=>1,'fecha_baja'=>'2026-01-04'])->assertUnprocessable();
    }
    public function test_formulario_no_muestra_acciones_sin_permiso(): void
    {
        $this->equipo(); $this->permiso(3,'maquinas.consultar'); $this->actingAs($this->usuario(3))->get('/maquinas')->assertOk()->assertDontSee('Nueva Máquina')->assertDontSee('Editar')->assertDontSee('Eliminar')->assertDontSee('Cambiar estado');
    }
    public function test_login_descarta_ruta_intended_y_ruta_de_inicio_manipulada(): void
    {
        $user=$this->usuario(2); $this->withSession(['url.intended'=>'/accesos/usuarios'])->post('/login',['email'=>$user->email,'password'=>'SeguraPrueba123!'])->assertRedirect(route('rentas.inicio'));
        DB::table('roles')->where('id_rol',2)->update(['ruta_inicio'=>'superadmin.inicio']); $this->get('/panel')->assertForbidden();
    }
    public function test_cambiar_estado_no_elude_validacion_de_cuentas(): void
    {
        $admin=$this->usuario(); $this->permiso(1,'usuarios.validar',false);
        $pending=$this->usuario(2,'pendiente'); $this->actingAs($admin);
        $this->patch('/accesos/usuarios/'.$pending->id,['operacion'=>'desactivar'])->assertUnprocessable();
        $this->patch('/accesos/usuarios/'.$pending->id,['operacion'=>'activar'])->assertUnprocessable();
        $this->assertSame('pendiente',$pending->fresh()->estado);
    }
}
