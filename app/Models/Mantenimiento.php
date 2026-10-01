<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mantenimiento extends Model
{
    protected $table = 'mantenimientos';

    protected $primaryKey = 'id_mantenimiento';

    public $timestamps = false;

    protected $fillable = [
        'id_maquina',
        'id_tipo_mantenimiento',
        'fecha_entrada',
        'fecha_salida',
        'descripcion',
        'costo',
        'observaciones'
    ];
}