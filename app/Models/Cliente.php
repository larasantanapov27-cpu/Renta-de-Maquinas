<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'clientes';

    protected $primaryKey = 'id_cliente';

    public $timestamps = false;

    protected $fillable = [
        'id_persona',
        'correo'
    ];

    public function persona()
    {
        return $this->belongsTo(
            Persona::class,
            'id_persona',
            'id_persona'
        );
    }

    public function direcciones()
    {
        return $this->hasMany(
            DireccionCliente::class,
            'id_cliente',
            'id_cliente'
        );
    }
}