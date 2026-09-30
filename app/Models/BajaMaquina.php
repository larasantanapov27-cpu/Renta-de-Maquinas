<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BajaMaquina extends Model
{
    protected $table = 'bajas_maquinas';

    protected $primaryKey = 'id_baja';

    public $timestamps = false;

    protected $fillable = [
        'id_maquina',
        'id_motivo_baja',
        'fecha_baja',
        'descripcion'
    ];
}