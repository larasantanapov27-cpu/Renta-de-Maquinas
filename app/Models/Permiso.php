<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Permiso extends Model
{
    protected $table = 'permisos';
    protected $primaryKey = 'id_permiso';
    public $timestamps = false;
    protected $guarded = ['id_permiso'];
    public function roles() { return $this->belongsToMany(Rol::class, 'rol_permiso', 'id_permiso', 'id_rol')->withPivot('activo')->withTimestamps(); }
}
