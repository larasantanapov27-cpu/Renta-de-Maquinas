<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Maquina extends Model
{
    protected $table = 'maquinas';

    protected $primaryKey = 'id_maquina';

    public $timestamps = false;

    protected $fillable = [
        'codigo',
        'id_tipo_maquina',
        'id_estado_maquina',
        'marca',
        'modelo',
        'numero_serie',
        'fecha_ingreso',
        'descripcion',
        'observaciones'
    ];

    public function tipoMaquina()
    {
        return $this->belongsTo(
            TipoMaquina::class,
            'id_tipo_maquina',
            'id_tipo_maquina'
        );
    }

    public function estadoMaquina()
    {
        return $this->belongsTo(
            EstadoMaquina::class,
            'id_estado_maquina',
            'id_estado_maquina'
        );
    }
}