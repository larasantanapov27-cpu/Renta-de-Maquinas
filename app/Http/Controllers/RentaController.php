<?php

namespace App\Http\Controllers;

use App\Models\Renta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RentaController extends Controller
{
    public function index(Request $request)
    {
        $data = $request->validate([
            'buscar' => ['nullable', 'string', 'max:100'],
        ]);

        $buscar = trim($data['buscar'] ?? '');

        // Calcular el total de los detalles de cada renta.
        // El depósito se incluye en el total a cubrir.
        $totalesDetalles = DB::table('detalle_rentas')
            ->select('id_renta')
            ->selectRaw('COUNT(*) AS cantidad_detalles')
            ->selectRaw('
                SUM(
                    ROUND(cantidad_periodos * precio_aplicado, 2)
                    + deposito_aplicado
                    - descuento
                    + cargo_adicional
                    + cargo_danio
                ) AS total_cubrir
            ')
            ->groupBy('id_renta');

        // Sumar todos los pagos de cada renta.
        $totalesPagos = DB::table('pagos')
            ->select('id_renta')
            ->selectRaw('SUM(monto) AS total_pagado')
            ->groupBy('id_renta');

        $rentas = Renta::query()
            ->leftJoin(
                'clientes',
                'rentas.id_cliente',
                '=',
                'clientes.id_cliente'
            )
            ->leftJoin(
                'personas',
                'clientes.id_persona',
                '=',
                'personas.id_persona'
            )
            ->leftJoinSub(
                $totalesDetalles,
                'totales_detalles',
                function ($join) {
                    $join->on(
                        'rentas.id_renta',
                        '=',
                        'totales_detalles.id_renta'
                    );
                }
            )
            ->leftJoinSub(
                $totalesPagos,
                'totales_pagos',
                function ($join) {
                    $join->on(
                        'rentas.id_renta',
                        '=',
                        'totales_pagos.id_renta'
                    );
                }
            )
            ->select([
                'rentas.*',
                'personas.nombre as cliente_nombre',
                'personas.apellidos as cliente_apellidos',
            ])
            ->selectRaw('
                COALESCE(totales_detalles.cantidad_detalles, 0)
                AS cantidad_detalles
            ')
            ->selectRaw('
                COALESCE(totales_detalles.total_cubrir, 0)
                AS total_cubrir
            ')
            ->selectRaw('
                COALESCE(totales_pagos.total_pagado, 0)
                AS total_pagado
            ')
            ->selectRaw('
                COALESCE(totales_detalles.total_cubrir, 0)
                - COALESCE(totales_pagos.total_pagado, 0)
                AS saldo
            ')
            ->selectRaw("
                CASE
                    WHEN COALESCE(
                        totales_detalles.cantidad_detalles, 0
                    ) = 0
                        THEN 'Sin calcular'

                    WHEN totales_detalles.total_cubrir < 0
                        OR COALESCE(totales_pagos.total_pagado, 0) < 0
                        THEN 'Revisar importes'

                    WHEN COALESCE(totales_pagos.total_pagado, 0)
                        >= totales_detalles.total_cubrir
                        THEN 'Liquidado'

                    ELSE 'Pendiente'
                END AS estado_pago
            ")
            ->with('estado')
            ->when($buscar !== '', function ($query) use ($buscar) {
                $query->where(function ($consulta) use ($buscar) {
                    $consulta
                        ->where(
                            'personas.nombre',
                            'like',
                            '%' . $buscar . '%'
                        )
                        ->orWhere(
                            'personas.apellidos',
                            'like',
                            '%' . $buscar . '%'
                        );

                    if (ctype_digit($buscar)) {
                        $consulta->orWhere(
                            'rentas.id_renta',
                            $buscar
                        );
                    }
                });
            })
            ->orderBy('rentas.id_renta', 'asc')
            ->paginate(10)
            ->withQueryString();

        return view('rentas.index', compact('rentas', 'buscar'));
    }
    public function create()
    {
        return view('rentas.formulario', $this->opciones());
    }
    private function opciones(): array
    {
        return ['clientes'=>DB::table('clientes as c')->join('personas as p','p.id_persona','=','c.id_persona')->select('c.id_cliente','p.nombre','p.apellidos')->get(),
            'maquinas'=>DB::table('maquinas')->select('id_maquina','codigo')->get(),
            'tarifas'=>DB::table('detalle_tarifa as t')->join('periodos_renta as p','p.id_periodo','=','t.id_periodo')->select('t.*','p.nombre as periodo')->get()];
    }
    public function store(Request $request)
    {
        $data=$request->validate([
            'id_cliente'=>'required|integer|exists:clientes,id_cliente', 'fecha_salida'=>'required|date',
            'fecha_devolucion_programada'=>'required|date|after:fecha_salida','observaciones'=>'nullable|string|max:5000',
            'id_estado_renta'=>'prohibited','fecha_devolucion_real'=>'prohibited',
            'detalles'=>'required|array|min:1|max:50','detalles.*'=>'array:id_maquina,id_tarifa,cantidad_periodos',
            'detalles.*.id_maquina'=>'required|integer|distinct|exists:maquinas,id_maquina',
            'detalles.*.id_tarifa'=>'required|integer|exists:detalle_tarifa,id_tarifa',
            'detalles.*.cantidad_periodos'=>'required|numeric|min:0.01|max:999999.99',
        ]);
        $detalles=$data['detalles']; unset($data['detalles']);
        return \App\Services\TransaccionSql::ejecutar(['rentas','detalle_rentas','maquinas','clientes','detalle_tarifa','estados_renta','estados_maquina','mantenimientos','bajas_maquinas'], function () use ($data, $detalles) {
            abort_unless(DB::table('clientes')->where('id_cliente',$data['id_cliente'])->lockForUpdate()->exists(),422,'El cliente ya no existe.');
            $ids=collect($detalles)->pluck('id_maquina')->sort()->values();
            $maquinas=DB::table('maquinas')->whereIn('id_maquina',$ids)->orderBy('id_maquina')->lockForUpdate()->get()->keyBy('id_maquina');
            $estado=DB::table('estados_renta')->where('nombre','Activa')->value('id_estado_renta');
            $rentada=DB::table('estados_maquina')->where('nombre','Rentada')->value('id_estado_maquina');
            abort_unless($estado && $rentada,422,'Faltan estados del catálogo.');
            $id=DB::table('rentas')->insertGetId($data+['id_estado_renta'=>$estado,'fecha_registro'=>now()]);
            foreach($detalles as $detalle) {
                $maquina=$maquinas->get($detalle['id_maquina']);
                abort_unless($maquina,422,'Una máquina ya no existe.');
                $disponible=DB::table('estados_maquina')->where('id_estado_maquina',$maquina->id_estado_maquina)->value('nombre')==='Disponible';
                if (!$disponible || !\App\Services\EstadoEquipo::libre($maquina->id_maquina)) throw \Illuminate\Validation\ValidationException::withMessages(['detalles'=>'Una máquina ya no está disponible.']);
                $tarifa=DB::table('detalle_tarifa')->where('id_tarifa',$detalle['id_tarifa'])->where('id_tipo_maquina',$maquina->id_tipo_maquina)->where('fecha_inicio','<=',substr($data['fecha_salida'],0,10))->where(fn($q)=>$q->whereNull('fecha_fin')->orWhere('fecha_fin','>=',substr($data['fecha_salida'],0,10)))->lockForUpdate()->first();
                if (!$tarifa) throw \Illuminate\Validation\ValidationException::withMessages(['detalles'=>'La tarifa no corresponde a la máquina o no está vigente.']);
                DB::table('detalle_rentas')->insert($detalle+['id_renta'=>$id,'precio_aplicado'=>$tarifa->precio,'deposito_aplicado'=>$tarifa->deposito]);
                DB::table('maquinas')->where('id_maquina',$maquina->id_maquina)->update(['id_estado_maquina'=>$rentada]);
            }
            return redirect()->route('panel')->with('success','Renta registrada con sus detalles.');
        });
    }
    public function edit($id)
    {
        $renta=Renta::with('detalles')->findOrFail($id);
        return view('rentas.formulario',['renta'=>$renta]+$this->opciones());
    }
    public function show($id)
    {
        return parent::show($id);
    }
    public function update(Request $request,$id)
    {
        $data=$request->validate(['observaciones'=>'nullable|string|max:5000','fecha_devolucion_programada'=>'required|date',
            'fecha_devolucion_real'=>'prohibited','id_estado_renta'=>'prohibited','id_cliente'=>'prohibited','fecha_salida'=>'prohibited','detalles'=>'prohibited']);
        $renta=Renta::lockForUpdate()->findOrFail($id);
        abort_unless(\Illuminate\Support\Carbon::parse($data['fecha_devolucion_programada'])->gt($renta->fecha_salida),422,'La devolución debe ser posterior a la salida.');
        $renta->update($data);
        return redirect()->route('panel')->with('success','Renta actualizada.');
    }
    public function devolucion($id)
    {
        $renta=Renta::with('detalles')->findOrFail($id);
        return view('rentas.devolucion',compact('renta'));
    }
    public function devolver(Request $request,$id)
    {
        $data=$request->validate(['detalles'=>'required|array|min:1','detalles.*'=>'integer|distinct','fecha_devolucion_real'=>'required|date|before_or_equal:now']);
        return \App\Services\TransaccionSql::ejecutar(['rentas','detalle_rentas','maquinas','estados_renta','estados_maquina','mantenimientos','bajas_maquinas'], function () use ($data, $id) {
            // Lock machines before the rental to share lock order with maintenance and machine changes.
            $ids=DB::table('detalle_rentas')->where('id_renta',$id)->pluck('id_maquina');
            DB::table('maquinas')->whereIn('id_maquina',$ids)->orderBy('id_maquina')->lockForUpdate()->get();
            $renta=Renta::lockForUpdate()->findOrFail($id);
            abort_unless($renta->estado->nombre==='Activa',422,'Solo se devuelven rentas activas.');
            abort_unless(\Illuminate\Support\Carbon::parse($data['fecha_devolucion_real'])->gte($renta->fecha_salida),422,'La devolución no puede preceder la salida.');
            $detalles=DB::table('detalle_rentas')->where('id_renta',$id)->whereIn('id_detalle_renta',$data['detalles'])->whereNull('fecha_devolucion_real')->lockForUpdate()->get();
            abort_unless($detalles->count()===count($data['detalles']),422,'Detalles inválidos o ya devueltos.');
            foreach($detalles as $detalle) {
                DB::table('detalle_rentas')->where('id_detalle_renta',$detalle->id_detalle_renta)->update(['fecha_devolucion_real'=>$data['fecha_devolucion_real']]);
                if (\App\Services\EstadoEquipo::libre($detalle->id_maquina)) {
                    $disponible=DB::table('estados_maquina')->where('nombre','Disponible')->value('id_estado_maquina');
                    \App\Services\EstadoEquipo::cambiar($detalle->id_maquina,$disponible);
                }
            }
            if (!DB::table('detalle_rentas')->where('id_renta',$id)->whereNull('fecha_devolucion_real')->exists()) $renta->update(['id_estado_renta'=>DB::table('estados_renta')->where('nombre','Finalizada')->value('id_estado_renta')]);
            return redirect()->route('panel')->with('success','Devolución registrada.');
        });
    }

}
