<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoMaquina extends Model
{
    protected $table = 'tipos_maquina';

    protected $primaryKey = 'id_tipo_maquina';

    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'descripcion'
    ];

    public function maquinas()
    {
        return $this->hasMany(
            Maquina::class,
            'id_tipo_maquina',
            'id_tipo_maquina'
        );
    }
}