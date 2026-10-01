<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $table = 'pagos';

    protected $primaryKey = 'id_pago';

    public $timestamps = false;

    protected $fillable = [
        'id_renta',
        'id_metodo_pago',
        'fecha_pago',
        'monto',
        'referencia',
        'observaciones',
    ];
}