<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DireccionCliente extends Model
{
    protected $table = 'direcciones_clientes';

    protected $primaryKey = 'id_direccion';

    public $timestamps = false;

    protected $fillable = [
        'id_cliente',
        'calle',
        'numero_exterior',
        'numero_interior',
        'colonia',
        'municipio',
        'estado',
        'codigo_postal'
    ];

    public function cliente()
    {
        return $this->belongsTo(
            Cliente::class,
            'id_cliente',
            'id_cliente'
        );
    }
}