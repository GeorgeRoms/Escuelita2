<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AlumnoCarrera
 *
 * @property $id
 * @property $alumno_no_control
 * @property $carrera_id
 * @property $estatus
 * @property $fecha_inicio
 * @property $fecha_fin
 * @property $created_at
 * @property $updated_at
 *
 * @property Alumno $alumno
 * @property Carrera $carrera
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class AlumnoCarrera extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'alumno_carrera';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [
        'alumno_no_control',
        'carrera_id',
        'estatus',        // 'Activo' | 'Baja'
        'fecha_inicio',
        'fecha_fin',
    ];

    // protected $casts = [
    //     'fecha_inicio' => 'date:Y-m-d',
    //     'fecha_fin'    => 'date:Y-m-d',
    // ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function alumno()
    {
        return $this->belongsTo(\App\Models\Alumno::class, 'alumno_no_control', 'no_control')
                ->withTrashed();
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function carrera()
    {
        return $this->belongsTo(Carrera::class, 'carrera_id', 'id_carrera');
    }
    
}
