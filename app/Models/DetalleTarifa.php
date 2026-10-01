<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleTarifa extends Model
{
    // Nombre real de la tabla
    protected $table = 'detalle_tarifa';

    // Llave primaria
    protected $primaryKey = 'id_tarifa';

    // La tabla no utiliza created_at ni updated_at
    public $timestamps = false;

    // Campos que se pueden registrar o modificar
    protected $fillable = [
        'id_tipo_maquina',
        'id_periodo',
        'precio',
        'deposito',
        'fecha_inicio',
        'fecha_fin',
    ];

    // Relación con tipos_maquina
    public function tipoMaquina()
    {
        return $this->belongsTo(
            TipoMaquina::class,
            'id_tipo_maquina',
            'id_tipo_maquina'
        );
    }

    // Relación con periodos_renta
    public function periodo()
    {
        return $this->belongsTo(
            PeriodoRenta::class,
            'id_periodo',
            'id_periodo'
        );
    }
}