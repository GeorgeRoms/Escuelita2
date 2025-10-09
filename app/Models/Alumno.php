<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\HasCustomPrimaryKey;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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
    protected $fillable = ['nombre','apellido_pat','apellido_mat','genero','fk_carrera','anio','periodo'];

    protected static function booted()
    {
        static::creating(function ($a) {
        $a->anio    = $a->anio ?? now()->year;
        $a->periodo = $a->periodo ?? 1;

        DB::transaction(function () use ($a) {
            $max = DB::table('alumnos')
                ->where('anio', $a->anio)
                ->where('periodo', $a->periodo)
                ->lockForUpdate()
                ->selectRaw(
                    // usa consecutivo si existe; si no, derivarlo del no_control; si no, 0
                    'COALESCE(MAX(consecutivo),
                              MAX(CAST(RIGHT(no_control, 4) AS UNSIGNED)),
                              0) as m'
                )
                ->value('m');

            $a->consecutivo = ((int)$max) + 1;
            $a->no_control  = sprintf('%04d%01d%04d', $a->anio, $a->periodo, $a->consecutivo);
        });
    });
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function carrera()
    {
        // alumnos.fk_carrera -> carreras.id_carrera
        return $this->belongsTo(\App\Models\Carrera::class, 'fk_carrera', 'id_carrera');
    }

    public function getNombreCompletoAttribute(): string
    {
        return trim("{$this->nombre} {$this->apellido_pat} " . ($this->apellido_mat ?? ''));
    }

    // (opcional) Etiqueta de género
    public function getGeneroLabelAttribute(): string
    {
        return $this->genero === 'M' ? 'Masculino' : ($this->genero === 'F' ? 'Femenino' : $this->genero);
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function contactosAlumno()
    {
        return $this->hasOne(\App\Models\ContactosAlumno::class, 'fk_alumno', 'no_control');
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
