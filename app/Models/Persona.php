<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $table = 'personas';

    protected $primaryKey = 'id_persona';

    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'apellidos',
        'telefono'
    ];

    public function cliente()
    {
        return $this->hasOne(
            Cliente::class,
            'id_persona',
            'id_persona'
        );
    }

    public function proveedor()
    {
        return $this->hasOne(
            Proveedor::class,
            'id_persona',
            'id_persona'
        );
    }
}