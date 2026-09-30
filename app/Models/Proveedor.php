<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    protected $table = 'proveedores';

    protected $primaryKey = 'id_proveedor';

    public $timestamps = false;

    protected $fillable = [
        'id_persona',
        'rfc',
        'correo',
        'direccion'
    ];

    public function persona()
    {
        return $this->belongsTo(
            Persona::class,
            'id_persona',
            'id_persona'
        );
    }
}