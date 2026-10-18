<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $ahora = now();

        // Cantidad de máquinas por estado.
        $maquinasPorEstado = DB::table('maquinas as m')
            ->join(
                'estados_maquina as e',
                'm.id_estado_maquina',
                '=',
                'e.id_estado_maquina'
            )
            ->select('e.nombre')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('e.nombre')
            ->pluck('total', 'nombre');

        $disponibles = $maquinasPorEstado->get('Disponible', 0);
        $rentadas = $maquinasPorEstado->get('Rentada', 0);
        $mantenimiento = $maquinasPorEstado->get('Mantenimiento', 0);

        $rentasActivas = DB::table('rentas as r')
            ->join(
                'estados_renta as e',
                'r.id_estado_renta',
                '=',
                'e.id_estado_renta'
            )
            ->where('e.nombre', 'Activa')
            ->count();

        $totalClientes = DB::table('clientes')->count();

        // Pagos recibidos durante el mes actual.
        $inicioMes = $ahora->copy()->startOfMonth();
        $siguienteMes = $inicioMes->copy()->addMonth();

        $pagosMes = DB::table('pagos')
            ->where('fecha_pago', '>=', $inicioMes)
            ->where('fecha_pago', '<', $siguienteMes)
            ->sum('monto');

        // Consulta base con el nombre real del cliente.
        $consultaRentas = DB::table('rentas as r')
            ->join('clientes as c', 'r.id_cliente', '=', 'c.id_cliente')
            ->join('personas as p', 'c.id_persona', '=', 'p.id_persona')
            ->join(
                'estados_renta as e',
                'r.id_estado_renta',
                '=',
                'e.id_estado_renta'
            );

        $columnas = [
            'r.id_renta',
            'r.fecha_salida',
            'r.fecha_devolucion_programada',
            'e.nombre as estado',
            DB::raw("CONCAT(p.nombre, ' ', p.apellidos) as cliente"),
        ];

        $ultimasRentas = (clone $consultaRentas)
            ->select($columnas)
            ->orderByDesc('r.fecha_registro')
            ->orderByDesc('r.id_renta')
            ->limit(5)
            ->get();

        // Rentas activas con al menos una máquina sin devolver.
        // EXISTS evita contar varias veces una misma renta.
        $pendientes = (clone $consultaRentas)
            ->where('e.nombre', 'Activa')
            ->whereExists(function ($query) {
                $query->selectRaw('1')
                    ->from('detalle_rentas as d')
                    ->whereColumn('d.id_renta', 'r.id_renta')
                    ->whereNull('d.fecha_devolucion_real');
            });

        $rentasAtrasadas = (clone $pendientes)
            ->where('r.fecha_devolucion_programada', '<', $ahora)
            ->count();

        // Incluye atrasadas y devoluciones de los próximos siete días.
        $devoluciones = (clone $pendientes)
            ->select($columnas)
            ->where(
                'r.fecha_devolucion_programada',
                '<=',
                $ahora->copy()->addDays(7)
            )
            ->orderBy('r.fecha_devolucion_programada')
            ->limit(5)
            ->get();

        return view('home', compact(
            'disponibles',
            'rentadas',
            'mantenimiento',
            'rentasActivas',
            'totalClientes',
            'pagosMes',
            'ultimasRentas',
            'rentasAtrasadas',
            'devoluciones',
            'ahora'
        ));
    }
}
