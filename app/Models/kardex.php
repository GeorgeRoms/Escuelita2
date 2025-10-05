<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\HasCustomPrimaryKey;

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

    use HasCustomPrimaryKey; // opcional, pero útil para binding y $modelo->id

    // 👇 Dile a Eloquent cómo se llama realmente tu tabla
    protected $table = 'kardex';

    // Ajusta tu PK real (cámbiala si es otra)
    protected $primaryKey = 'id_kardex';
    public $incrementing = true;   // false si tu PK no es autoincremental
    protected $keyType = 'int';
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['id_kardex', 'fk_alumno', 'fk_curso', 'fecha_inscri', 'estado', 'promedio', 'oportunidad', 'intento', 'semestre', 'unidades_reprobadas'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function alumno()
    {
        return $this->belongsTo(\App\Models\Alumno::class, 'fk_alumno', 'no_control');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function curso()
    {
        return $this->belongsTo(\App\Models\Curso::class, 'fk_curso', 'id_curso');
    }
    
}
