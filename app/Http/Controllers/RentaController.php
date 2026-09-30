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
}