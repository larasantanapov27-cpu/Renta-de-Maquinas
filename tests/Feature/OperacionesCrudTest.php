<?php
namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Services\RegistroCrud;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Events\QueryExecuted;
use PHPUnit\Framework\Attributes\DataProvider;

class OperacionesCrudTest extends TestCase
{
    private ?string $fallar = null;
    private array $sql = [];
    protected function setUp(): void
    {
        parent::setUp();
        if (DB::getDriverName() === 'sqlite') {
            $this->assertSame(':memory:', DB::connection()->getDatabaseName());
            DB::unprepared(file_get_contents(__DIR__.'/../Fixtures/schema.sql'));
        } else {
            $this->assertStringStartsWith('renta_pruebas_', DB::connection()->getDatabaseName());
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            foreach (array_keys(RegistroCrud::LLAVES) as $tabla) DB::table($tabla)->delete();
            foreach (['rol_permiso','permisos','users','roles'] as $tabla) DB::table($tabla)->delete();
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }
        DB::unprepared(file_get_contents(__DIR__.'/../Fixtures/catalogos.sql'));
        foreach (DB::table('permisos')->pluck('id_permiso') as $id) {
            DB::table('rol_permiso')->updateOrInsert(['id_rol'=>1,'id_permiso'=>$id],['activo'=>1]);
        }
        $user = new User(['name'=>'Prueba CRUD','email'=>'crud@example.test','password'=>bcrypt('Prueba123!')]);
        $user->id_rol=1; $user->estado='activo'; $user->save(); $this->actingAs($user);
        DB::table('personas')->insert(['id_persona'=>9001,'nombre'=>'Prueba','apellidos'=>'Aislada','telefono'=>'0000000000']);
        DB::table('clientes')->insert(['id_cliente'=>9001,'id_persona'=>9001]);
        DB::table('maquinas')->insert(['id_maquina'=>9001,'codigo'=>'TEST-9001','id_tipo_maquina'=>1,'id_estado_maquina'=>1,'fecha_ingreso'=>'2026-01-01']);
        DB::table('detalle_tarifa')->insert(['id_tarifa'=>9001,'id_tipo_maquina'=>1,'id_periodo'=>1,'precio'=>100,'deposito'=>50,'fecha_inicio'=>'2026-01-01']);
        DB::listen(function (QueryExecuted $query) {
            $this->sql[]=$query->sql;
            if ($this->fallar && str_contains(strtolower($query->sql), $this->fallar)) {
                $this->fallar=null;
                throw new \RuntimeException('Fallo controlado de prueba después de modificar datos');
            }
        });
    }
    public static function casos(): array
    {
        return [['renta',false],['renta',true],['devolucion',false],['devolucion',true],['mantenimiento',false],['mantenimiento',true]];
    }
    private function snapshot(): array
    {
        $datos=[];
        foreach (['rentas'=>'id_renta','detalle_rentas'=>'id_detalle_renta','maquinas'=>'id_maquina','mantenimientos'=>'id_mantenimiento'] as $tabla=>$id) {
            $datos[$tabla]=DB::table($tabla)->orderBy($id)->get()->map(fn($r)=>(array)$r)->all();
        }
        return $datos;
    }
    #[DataProvider('casos')]
    public function test_commit_y_rollback(string $operacion, bool $fallo): void
    {
        if ($operacion==='devolucion') {
            DB::table('rentas')->insert(['id_renta'=>9001,'id_cliente'=>9001,'id_estado_renta'=>2,'fecha_salida'=>'2026-01-02 10:00:00','fecha_devolucion_programada'=>'2026-01-03 10:00:00']);
            DB::table('detalle_rentas')->insert(['id_detalle_renta'=>9001,'id_renta'=>9001,'id_maquina'=>9001,'id_tarifa'=>9001,'cantidad_periodos'=>1,'precio_aplicado'=>100]);
            DB::table('maquinas')->where('id_maquina',9001)->update(['id_estado_maquina'=>2]);
        }
        $antes=$this->snapshot(); $this->sql=[];
        if ($fallo) $this->fallar=match($operacion) {'renta'=>'insert into `detalle_rentas`','devolucion'=>'update `detalle_rentas`',default=>'insert into `mantenimientos`'};
        if (DB::getDriverName()==='sqlite' && $this->fallar) $this->fallar=str_replace('`','"',$this->fallar);
        $respuesta=match($operacion) {
            'renta'=>$this->post('/rentas',['id_cliente'=>9001,'fecha_salida'=>'2026-01-02 10:00:00','fecha_devolucion_programada'=>'2026-01-03 10:00:00','detalles'=>[['id_maquina'=>9001,'id_tarifa'=>9001,'cantidad_periodos'=>1]]]),
            'devolucion'=>$this->patch('/rentas/9001/devolver',['detalles'=>[9001],'fecha_devolucion_real'=>'2026-01-03 10:00:00']),
            default=>$this->post('/mantenimientos',['id_maquina'=>9001,'id_tipo_mantenimiento'=>1,'fecha_entrada'=>'2026-01-02 10:00:00','descripcion'=>'Mantenimiento de prueba','costo'=>10]),
        };
        $respuesta->assertRedirect();
        $sentencias=$this->sql; $despues=$this->snapshot();
        $this->assertContains(DB::getDriverName()==='mysql'?'START TRANSACTION':'BEGIN TRANSACTION',$sentencias);
        if ($fallo) {
            $respuesta->assertSessionHasErrors('operacion');
            $this->assertSame($antes,$despues);
            $this->assertContains('ROLLBACK',$sentencias);
            $this->assertNotContains('COMMIT',$sentencias);
        } else {
            $respuesta->assertSessionHasNoErrors()->assertSessionHas('success');
            $this->assertContains('COMMIT',$sentencias);
            $this->assertNotContains('ROLLBACK',$sentencias);
            $estado=DB::table('maquinas')->where('id_maquina',9001)->value('id_estado_maquina');
            $this->assertEquals(match($operacion){'renta'=>2,'devolucion'=>1,default=>3},$estado);
            if ($operacion==='renta') { $this->assertCount(1,$despues['rentas']); $this->assertCount(1,$despues['detalle_rentas']); }
            if ($operacion==='devolucion') { $this->assertNotNull($despues['detalle_rentas'][0]['fecha_devolucion_real']); $this->assertEquals(3,$despues['rentas'][0]['id_estado_renta']); }
            if ($operacion==='mantenimiento') $this->assertCount(1,$despues['mantenimientos']);
        }
        if (getenv('CRUD_EVIDENCIA')) {
            file_put_contents(getenv('CRUD_EVIDENCIA'),json_encode(['operacion'=>$operacion,'resultado'=>$fallo?'ROLLBACK':'COMMIT','antes'=>$antes,'despues'=>$despues,'sql'=>$sentencias],JSON_UNESCAPED_UNICODE)."\n",FILE_APPEND);
        }
    }
    public function test_crud_catalogo_y_autorizacion(): void
    {
        $this->post('/tipos_maquina',['nombre'=>'Tipo prueba CRUD','descripcion'=>'Detalle'])->assertSessionHasNoErrors();
        $id=DB::table('tipos_maquina')->where('nombre','Tipo prueba CRUD')->value('id_tipo_maquina');
        $this->get('/tipos_maquina/'.$id)->assertOk()->assertSee('Detalle');
        $this->get('/tipos_maquina/'.$id.'/edit')->assertOk();
        $this->put('/tipos_maquina/'.$id,['nombre'=>'Tipo editado','descripcion'=>'Actualizado'])->assertSessionHasNoErrors();
        $this->get('/tipos_maquina')->assertOk()->assertSee('Borrar')->assertSee('confirm(' ,false);
        $permiso=DB::table('permisos')->where('clave','catalogos.eliminar')->value('id_permiso');
        DB::table('rol_permiso')->where('id_rol',1)->where('id_permiso',$permiso)->update(['activo'=>0]);
        $this->delete('/tipos_maquina/'.$id)->assertForbidden();
        DB::table('rol_permiso')->where('id_rol',1)->where('id_permiso',$permiso)->update(['activo'=>1]);
        $this->delete('/tipos_maquina/1')->assertSessionHasErrors('registro');
        $this->delete('/tipos_maquina/'.$id)->assertSessionHas('success');
        $this->assertFalse(DB::table('tipos_maquina')->where('id_tipo_maquina',$id)->exists());
        $this->delete('/maquinas/9001')->assertSessionHasErrors('registro');
    }
    public function test_crear_ver_editar_y_borrar_modulos(): void
    {
        $persona=['nombre'=>'Persona CRUD','apellidos'=>'Prueba','telefono'=>'0000000000'];
        $casos=[
            'clientes'=>['persona'=>$persona,'correo'=>'cliente@example.test'],
            'proveedores'=>['persona'=>$persona,'correo'=>'proveedor@example.test','rfc'=>'PRUEBA','direccion'=>'Dirección de prueba'],
            'direcciones_clientes'=>['id_cliente'=>9001,'calle'=>'Calle de prueba'],
            'maquinas'=>['codigo'=>'CRUD-9002','id_tipo_maquina'=>1,'id_estado_maquina'=>1,'marca'=>'Prueba','fecha_ingreso'=>'2026-01-01'],
            'detalle_tarifa'=>['id_tipo_maquina'=>1,'id_periodo'=>1,'precio'=>200,'deposito'=>50,'fecha_inicio'=>'2026-01-01','fecha_fin'=>'2026-12-31'],
            'tipos_maquina'=>['nombre'=>'Tipo CRUD','descripcion'=>'Prueba'],
            'estados_maquina'=>['nombre'=>'Estado CRUD','descripcion'=>'Prueba'],
            'estados_renta'=>['nombre'=>'Estado CRUD','descripcion'=>'Prueba'],
            'periodos_renta'=>['nombre'=>'Periodo CRUD'],
            'metodos_pago'=>['nombre'=>'Método CRUD'],
            'tipos_mantenimiento'=>['nombre'=>'Tipo CRUD','descripcion'=>'Prueba'],
            'motivos_baja'=>['nombre'=>'Motivo CRUD','descripcion'=>'Prueba'],
            'mantenimientos'=>['id_maquina'=>9001,'id_tipo_mantenimiento'=>1,'fecha_entrada'=>'2026-01-01 10:00:00','fecha_salida'=>'2026-01-02 10:00:00','descripcion'=>'Prueba','costo'=>10],
        ];
        foreach($casos as $modulo=>$data) {
            $this->post(route($modulo.'.store'),$data)->assertRedirect()->assertSessionHasNoErrors()->assertSessionHas('success');
            $key=RegistroCrud::LLAVES[$modulo]; $id=DB::table($modulo)->max($key);
            $this->get(route($modulo.'.show',$id))->assertOk();
            $this->get(route($modulo.'.edit',$id))->assertOk();
            if(isset($data['descripcion'])) $data['descripcion']='Editado CRUD';
            elseif(isset($data['persona'])) $data['persona']['nombre']='Persona editada';
            elseif(isset($data['calle'])) $data['calle']='Calle editada';
            elseif(isset($data['nombre'])) $data['nombre']='Nombre editado';
            elseif(isset($data['precio'])) $data['precio']=250;
            $this->put(route($modulo.'.update',$id),$data)->assertRedirect()->assertSessionHasNoErrors()->assertSessionHas('success');
            $actual=(array)DB::table($modulo)->where($key,$id)->first();
            foreach($data as $campo=>$valor) if($campo!=='persona') $this->assertEquals($valor,$actual[$campo]);
            if(isset($data['persona'])) $this->assertEquals('Persona editada',DB::table('personas')->where('id_persona',$actual['id_persona'])->value('nombre'));
            if(RegistroCrud::eliminable($modulo)) $this->delete(route($modulo.'.destroy',$id))->assertSessionHas('success');
        }
        // Operaciones históricas: editar y consultar sin borrado físico.
        DB::table('proveedores')->insert(['id_proveedor'=>9001,'id_persona'=>9001]);
        $compra=['id_maquina'=>9001,'id_proveedor'=>9001,'fecha_compra'=>'2026-01-01','precio_compra'=>1000];
        $this->post('/compras',$compra)->assertSessionHasNoErrors()->assertSessionHas('success');
        $id=DB::table('compras')->max('id_compra');
        $this->get('/compras/'.$id)->assertOk(); $this->get('/compras/'.$id.'/edit')->assertOk();
        $compra['precio_compra']=1100;
        $this->put('/compras/'.$id,$compra)->assertSessionHasNoErrors();
        $this->assertEquals(1100,DB::table('compras')->where('id_compra',$id)->value('precio_compra'));
        $this->delete('/compras/'.$id)->assertSessionHasErrors('registro');
        $this->post('/rentas',['id_cliente'=>9001,'fecha_salida'=>'2026-01-03 10:00:00','fecha_devolucion_programada'=>'2026-01-04 10:00:00','detalles'=>[['id_maquina'=>9001,'id_tarifa'=>9001,'cantidad_periodos'=>1]]])->assertSessionHasNoErrors();
        $renta=DB::table('rentas')->max('id_renta');
        $this->get('/pagos/renta/'.$renta)->assertOk()->assertViewHas('total',150);
        $this->get('/rentas/'.$renta)->assertOk(); $this->get('/rentas/'.$renta.'/edit')->assertOk();
        $this->put('/rentas/'.$renta,['fecha_devolucion_programada'=>'2026-01-05 10:00:00','observaciones'=>'Actualizada'])->assertSessionHasNoErrors();
        $pago=['id_renta'=>$renta,'id_metodo_pago'=>1,'fecha_pago'=>'2026-01-03 10:00:00','monto'=>50,'referencia'=>'Prueba','observaciones'=>'Prueba'];
        $this->post('/pagos',$pago)->assertSessionHasNoErrors();
        $id=DB::table('pagos')->max('id_pago'); $pago['monto']=75;
        $this->get('/pagos/'.$id)->assertOk(); $this->get('/pagos/'.$id.'/edit')->assertOk();
        $this->put('/pagos/'.$id,$pago)->assertSessionHasNoErrors();
        $this->assertEquals(75,DB::table('pagos')->where('id_pago',$id)->value('monto'));
        $this->delete('/pagos/'.$id)->assertSessionHasErrors('registro');
        $detalle=DB::table('detalle_rentas')->where('id_renta',$renta)->value('id_detalle_renta');
        $this->patch('/rentas/'.$renta.'/devolver',['detalles'=>[$detalle],'fecha_devolucion_real'=>'2026-01-04 10:00:00'])->assertSessionHasNoErrors();
        $this->post('/bajas-maquinas',['id_maquina'=>9001,'id_motivo_baja'=>1,'fecha_baja'=>'2026-01-05','descripcion'=>'Prueba'])->assertSessionHasNoErrors()->assertSessionHas('success');
        $id=DB::table('bajas_maquinas')->max('id_baja');
        $this->get('/bajas-maquinas/'.$id)->assertOk(); $this->get('/bajas-maquinas/'.$id.'/edit')->assertOk();
        $this->put('/bajas-maquinas/'.$id,['id_motivo_baja'=>1,'fecha_baja'=>'2026-01-05','descripcion'=>'Editada'])->assertSessionHasNoErrors();
        $this->assertEquals('Editada',DB::table('bajas_maquinas')->where('id_baja',$id)->value('descripcion'));
        $this->delete('/bajas-maquinas/'.$id)->assertSessionHasErrors('registro');
    }
    public function test_listas_formularios_y_detalles(): void
    {
        foreach (array_keys(RegistroCrud::LLAVES) as $modulo) {
            if (in_array($modulo,['personas','detalle_rentas'],true)) continue;
            $this->get(route($modulo.'.index'))->assertOk();
            $this->get(route($modulo.'.create'))->assertOk();
            $id=DB::table($modulo)->value(RegistroCrud::LLAVES[$modulo]);
            if ($id) {
                $this->get(route($modulo.'.show',$id))->assertOk();
                $this->get(route($modulo.'.edit',$id))->assertOk();
            }
        }
    }
}
