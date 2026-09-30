<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Compras extends Model
{
    protected $table = 'compras';

    protected $primaryKey = 'id_compra';

    public $timestamps = false;

    protected $fillable = [
        'id_proveedor',
        'id_maquina',
        'fecha_compra',
        'precio_compra',
        'numero_factura',
        'observaciones'
    ];

    public function proveedor()
    {
        return $this->belongsTo(
            Proveedor::class,
            'id_proveedor',
            'id_proveedor'
        );
    }

    public function maquina()
    {
        return $this->belongsTo(
            Maquina::class,
            'id_maquina',
            'id_maquina'
        );
    }
}