<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Rol extends Model
{
    protected $table = 'roles';
    protected $primaryKey = 'id_rol';
    protected $guarded = ['id_rol'];
    protected function casts(): array { return ['activo' => 'boolean']; }
    public function usuarios() { return $this->hasMany(User::class, 'id_rol', 'id_rol'); }
    public function permisos() { return $this->belongsToMany(Permiso::class, 'rol_permiso', 'id_rol', 'id_permiso')->withPivot('activo')->withTimestamps(); }
}
