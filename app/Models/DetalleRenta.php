<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetalleRenta extends Model
{
    protected $table = 'detalle_rentas';

    protected $primaryKey = 'id_detalle_renta';

    public $timestamps = false;

    protected $fillable = [
        'id_renta',
        'id_maquina',
        'id_tarifa',
        'cantidad_periodos',
        'precio_aplicado',
        'deposito_aplicado',
        'descuento',
        'cargo_retraso',
        'cargo_danio',
        'fecha_devolucion_real',
        'observaciones',
    ];

    protected $casts = [
        'cantidad_periodos' => 'decimal:2',
        'precio_aplicado' => 'decimal:2',
        'deposito_aplicado' => 'decimal:2',
        'descuento' => 'decimal:2',
        'cargo_retraso' => 'decimal:2',
        'cargo_danio' => 'decimal:2',
        'fecha_devolucion_real' => 'datetime',
    ];

    public function renta(): BelongsTo
    {
        return $this->belongsTo(
            Renta::class,
            'id_renta',
            'id_renta'
        );
    }
}