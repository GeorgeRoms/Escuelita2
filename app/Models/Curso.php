<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\HasCustomPrimaryKey;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Curso
 *
 * @property $id_curso
 * @property $cupo
 * @property $fk_materia
 * @property $fk_profesor
 * @property $fk_edificio
 * @property $created_at
 * @property $updated_at
 *
 * @property Kardex[] $kardexes
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Curso extends Model
{

    use HasCustomPrimaryKey;
    use SoftDeletes;

    protected $table = 'cursos';
    protected $primaryKey = 'id_curso';
    public $incrementing = true;
    protected $keyType = 'int';
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'cupo',
        'fk_materia',
        'fk_profesor',
        'aula_id',
        'periodo_id',
        'turno',
        'grupo',
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function kardexes()
    {
        return $this->hasMany(\App\Models\Kardex::class, 'id_curso', 'fk_curso');
    }

    public function materia()
    {
        return $this->belongsTo(\App\Models\Materia::class, 'fk_materia', 'id_materia');
    }

    public function profesor()
    {
        // tu modelo se llama Profesore
        return $this->belongsTo(\App\Models\Profesore::class, 'fk_profesor', 'id_profesor');
    }

    public function carrera()
    {
        return $this->belongsTo(\App\Models\Carrera::class, 'fk_carrera', 'id_carrera');
    }

    // (opcional) si curso guarda edificio o salón
    public function edificio()
    {
        // cambia 'fk_edificio' y PK de edificios según tu esquema
        return $this->belongsTo(\App\Models\Edificio::class, 'fk_edificio', 'edificio');
    }

    public function aula()
    { 
        return $this->belongsTo(Aula::class,'aula_id'); 
    }

    public function periodo()
    { 
        return $this->belongsTo(Periodo::class,'periodo_id'); 
    }

    public function inscripciones()
    { 
        return $this->hasMany(\App\Models\Inscripcione::class,'curso_id','id_curso');
    }
    
}
