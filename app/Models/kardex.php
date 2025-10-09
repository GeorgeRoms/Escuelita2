<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Kardex
 *
 * @property $id_kardex
 * @property $fk_alumno
 * @property $fk_curso
 * @property $fecha_inscri
 * @property $estado
 * @property $promedio
 * @property $oportunidad
 * @property $intento
 * @property $semestre
 * @property $unidades_reprobadas
 * @property $created_at
 * @property $updated_at
 *
 * @property Alumno $alumno
 * @property Curso $curso
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Kardex extends Model
{

    protected $table = 'kardex';
    protected $primaryKey = 'id_kardex';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $perPage = 20;

    protected $fillable = [
        'fk_alumno',
        'fk_curso',
        'fecha_inscri',
        'estado',
        'promedio',
        'oportunidad',
        'intento',
        'semestre',
        'unidades_reprobadas',
        // Si tu columna existe, descomenta/ajusta:
        // 'creditos_aprobados',
    ];

    /** Alumno relacionado (no_control) */
    public function alumno()
    {
        return $this->belongsTo(\App\Models\Alumno::class, 'fk_alumno', 'no_control');
    }

    /** Curso relacionado (id_curso) */
    public function curso()
    {
        return $this->belongsTo(\App\Models\Curso::class, 'fk_curso', 'id_curso');
    }
    
}
