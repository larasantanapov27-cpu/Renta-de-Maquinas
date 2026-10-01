<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EstadoRenta extends Model
{
    protected $table = 'estados_renta';

    protected $primaryKey = 'id_estado_renta';

    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    public function rentas(): HasMany
    {
        return $this->hasMany(
            Renta::class,
            'id_estado_renta',
            'id_estado_renta'
        );
    }
}