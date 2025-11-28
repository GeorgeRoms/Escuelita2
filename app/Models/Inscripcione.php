<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Inscripcione
 *
 * @property $id
 * @property $alumno_no_control
 * @property $curso_id
 * @property $estado
 * @property $oportunidad
 * @property $intento
 * @property $semestre
 * @property $created_at
 * @property $updated_at
 *
 * @property Alumno $alumno
 * @property Curso $curso
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Inscripcione extends Model
{
    
    protected $perPage = 20;
    protected $table = 'inscripciones';
    protected $primaryKey = 'id';
    public $incrementing = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'alumno_no_control',
        'curso_id',
        'estado',              // 'Inscrito' | 'Baja'
        'intento',             // 'Normal' | 'Repite' | 'Especial'
        'promedio',
    ];

    protected $casts = [
        'promedio' => 'decimal:2', // ⬅️ 2 decimales
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function alumno()
    {
        return $this->belongsTo(Alumno::class, 'alumno_no_control', 'no_control');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function curso()
    {
        return $this->belongsTo(Curso::class, 'curso_id', 'id_curso');
    }


    protected static function booted()
    {
        static::creating(function ($m) {
            if ($m->promedio === null || $m->promedio === '') {
            $m->promedio = 100;
        }
        });
    }

        public function getPromedioTextoAttribute()
    {
        return is_null($this->promedio)
            ? 'En curso...'
            : $this->promedio;
    }


    
}
