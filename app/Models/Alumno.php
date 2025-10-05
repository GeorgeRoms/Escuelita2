<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\HasCustomPrimaryKey;

/**
 * Class Alumno
 *
 * @property $no_control
 * @property $nombre
 * @property $apellido_pat
 * @property $apellido_mat
 * @property $genero
 * @property $fk_carrera
 * @property $created_at
 * @property $updated_at
 *
 * @property Carrera $carrera
 * @property ContactosAlumno $contactosAlumno
 * @property Historial $historial
 * @property Kardex[] $kardexes
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Alumno extends Model
{
    use HasCustomPrimaryKey;

    protected $primaryKey = 'no_control';
    public $incrementing = false;     // no_control no es autoincremental
    protected $keyType = 'string';

    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['no_control', 'nombre', 'apellido_pat', 'apellido_mat', 'genero', 'fk_carrera'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function carrera()
    {
        return $this->belongsTo(\App\Models\Carrera::class, 'fk_carrera', 'id_carrera');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function contactosAlumno()
    {
        return $this->hasOne(\App\Models\ContactosAlumno::class, 'no_control', 'id_contacto');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function historial()
    {
        return $this->hasOne(\App\Models\Historial::class, 'no_control', 'fk_alumno');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function kardexes()
    {
        return $this->hasMany(\App\Models\Kardex::class, 'no_control', 'fk_alumno');
    }
    
}
