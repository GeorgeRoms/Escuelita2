<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Carrera extends Model
{
    protected $table = 'carreras';

    // 👇 1) DI la PK correcta (para find/firstOrFail/etc.)
    protected $primaryKey = 'id_carrera';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $perPage = 20;

    // 👇 2) No metas la PK en fillable si es autoincremental
    protected $fillable = ['nombre_carr', 'capacidad'];

    // 👇 3) Para que el route model binding use id_carrera (show/edit/destroy)
    public function getRouteKeyName(): string
    {
        return 'id_carrera';
    }

    // 👇 4) (Opcional, súper útil) Alias para que $carrera->id funcione
    public function getIdAttribute()
    {
        return $this->attributes['id_carrera'];
    }

    // Relación corregida con la mínima edición
    public function alumnos()
    {
        // alumnos.fk_carrera -> carreras.id_carrera
        return $this->hasMany(\App\Models\Alumno::class, 'fk_carrera', 'id_carrera');
    }
}
