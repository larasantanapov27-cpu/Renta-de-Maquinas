<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Renta extends Model
{
    protected $table = 'rentas';

    protected $primaryKey = 'id_renta';

    public $timestamps = false;

    protected $fillable = [
        'id_cliente',
        'id_estado_renta',
        'fecha_registro',
        'fecha_salida',
        'fecha_devolucion_programada',
        'observaciones',
    ];

    protected $casts = [
        'fecha_registro' => 'datetime',
        'fecha_salida' => 'datetime',
        'fecha_devolucion_programada' => 'datetime',
    ];

    public function detalles(): HasMany
    {
        return $this->hasMany(
            DetalleRenta::class,
            'id_renta',
            'id_renta'
        );
    }

    public function estado(): BelongsTo
    {
        return $this->belongsTo(
            EstadoRenta::class,
            'id_estado_renta',
            'id_estado_renta'
        );
    }
}