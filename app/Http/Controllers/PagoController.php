<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PagoController extends Controller
{
    // LISTA GENERAL DE PAGOS
    public function index(Request $request)
    {
        $query = DB::table('pagos as p')
            ->join('rentas as r', 'p.id_renta', '=', 'r.id_renta')
            ->join('clientes as c', 'r.id_cliente', '=', 'c.id_cliente')
            ->join('personas as pe', 'c.id_persona', '=', 'pe.id_persona')
            ->join(
                'metodos_pago as mp',
                'p.id_metodo_pago',
                '=',
                'mp.id_metodo_pago'
            )
            ->select(
                'p.*',
                'pe.nombre',
                'pe.apellidos',
                'mp.nombre as metodo_pago'
            );

        // FILTRO POR NÚMERO DE RENTA
        if ($request->filled('renta')) {
            $query->where('r.id_renta', $request->renta);
        }

        // FILTRO POR FECHA
        if ($request->filled('fecha')) {
            $query->whereDate('p.fecha_pago', $request->fecha);
        }

        $pagos = $query
            ->orderByDesc('p.fecha_pago')
            ->get();

        return view('pagos.index', compact('pagos'));
    }


    // FORMULARIO PARA REGISTRAR PAGO
    public function create()
    {
        $rentas = DB::table('rentas as r')
            ->join('clientes as c', 'r.id_cliente', '=', 'c.id_cliente')
            ->join('personas as p', 'c.id_persona', '=', 'p.id_persona')
            ->select(
                'r.id_renta',
                'p.nombre',
                'p.apellidos'
            )
            ->orderByDesc('r.id_renta')
            ->get();

        $metodos = DB::table('metodos_pago')
            ->orderBy('nombre')
            ->get();

        return view(
            'pagos.create',
            compact('rentas', 'metodos')
        );
    }


    // GUARDAR PAGO
    public function store(Request $request)
    {
        $request->validate([
            'id_renta' => 'required|exists:rentas,id_renta',
            'id_metodo_pago' => 'required|exists:metodos_pago,id_metodo_pago',
            'fecha_pago' => 'required|date',
            'monto' => 'required|numeric|min:0.01',
            'referencia' => 'required|string|max:100',
            'observaciones' => 'required|string|max:255',
        ]);

        Pago::create(
            $request->only([
                'id_renta',
                'id_metodo_pago',
                'fecha_pago',
                'monto',
                'referencia',
                'observaciones',
            ])
        );

        return redirect()
            ->route('pagos.index')
            ->with(
                'success',
                'Pago registrado correctamente.'
            );
    }


    // FORMULARIO PARA EDITAR
    public function edit($id)
    {
        $pago = Pago::findOrFail($id);

        $rentas = DB::table('rentas as r')
            ->join('clientes as c', 'r.id_cliente', '=', 'c.id_cliente')
            ->join('personas as p', 'c.id_persona', '=', 'p.id_persona')
            ->select(
                'r.id_renta',
                'p.nombre',
                'p.apellidos'
            )
            ->orderByDesc('r.id_renta')
            ->get();

        $metodos = DB::table('metodos_pago')
            ->orderBy('nombre')
            ->get();

        return view(
            'pagos.edit',
            compact(
                'pago',
                'rentas',
                'metodos'
            )
        );
    }


    // ACTUALIZAR PAGO
    public function update(Request $request, $id)
    {
        $request->validate([
            'id_renta' => 'required|exists:rentas,id_renta',
            'id_metodo_pago' => 'required|exists:metodos_pago,id_metodo_pago',
            'fecha_pago' => 'required|date',
            'monto' => 'required|numeric|min:0.01',
            'referencia' => 'required|string|max:100',
            'observaciones' => 'required|string|max:255',
        ]);

        $pago = Pago::findOrFail($id);

        $pago->update(
            $request->only([
                'id_renta',
                'id_metodo_pago',
                'fecha_pago',
                'monto',
                'referencia',
                'observaciones',
            ])
        );

        return redirect()
            ->route('pagos.index')
            ->with(
                'success',
                'Pago actualizado correctamente.'
            );
    }


    // ELIMINAR PAGO
    public function destroy($id)
    {
        $pago = Pago::findOrFail($id);

        $pago->delete();

        return redirect()
            ->route('pagos.index')
            ->with(
                'success',
                'Pago eliminado correctamente.'
            );
    }


    // PAGOS ASOCIADOS A UNA RENTA
    public function renta($id)
    {
        // OBTENER RENTA Y CLIENTE
        $renta = DB::table('rentas as r')
            ->join(
                'clientes as c',
                'r.id_cliente',
                '=',
                'c.id_cliente'
            )
            ->join(
                'personas as p',
                'c.id_persona',
                '=',
                'p.id_persona'
            )
            ->where('r.id_renta', $id)
            ->select(
                'r.id_renta',
                'p.nombre',
                'p.apellidos'
            )
            ->first();

        abort_if(!$renta, 404);


        // OBTENER DETALLES DE LA RENTA
        $detalles = DB::table('detalle_rentas')
            ->where('id_renta', $id)
            ->get();


        // CALCULAR TOTAL
        $total = $detalles->sum(function ($detalle) {

            return
                ($detalle->cantidad_periodos
                    * $detalle->precio_aplicado)
                - $detalle->descuento
                + $detalle->cargo_adicional
                + $detalle->cargo_danio;
        });


        // HISTORIAL DE PAGOS
        $pagos = DB::table('pagos as p')
            ->join(
                'metodos_pago as mp',
                'p.id_metodo_pago',
                '=',
                'mp.id_metodo_pago'
            )
            ->where('p.id_renta', $id)
            ->select(
                'p.*',
                'mp.nombre as metodo_pago'
            )
            ->orderByDesc('p.fecha_pago')
            ->get();


        // TOTAL PAGADO
        $pagado = $pagos->sum('monto');


        // SALDO PENDIENTE
        $saldo = max(
            $total - $pagado,
            0
        );


        return view(
            'pagos.renta',
            compact(
                'renta',
                'total',
                'pagado',
                'saldo',
                'pagos'
            )
        );
    }
}