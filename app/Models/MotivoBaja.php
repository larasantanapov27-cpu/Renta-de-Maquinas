<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MotivoBaja extends Model
{
    protected $table = 'motivos_baja';

    protected $primaryKey = 'id_motivo_baja';

    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'descripcion'
    ];
}