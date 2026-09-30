<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstadoMaquina extends Model
{
    protected $table = 'estados_maquina';

    protected $primaryKey = 'id_estado_maquina';

    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'descripcion'
    ];

    public function maquinas()
    {
        return $this->hasMany(
            Maquina::class,
            'id_estado_maquina',
            'id_estado_maquina'
        );
    }
}