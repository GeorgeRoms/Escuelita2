<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\HasCustomPrimaryKey;

/**
 * Class Historial
 *
 * @property $fk_alumno
 * @property $fecha_apertura
 * @property $observaciones
 * @property $created_at
 * @property $updated_at
 *
 * @property Alumno $alumno
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Historial extends Model
{

    use HasCustomPrimaryKey; // opcional, pero útil para binding y $modelo->id

    // 👇 Dile a Eloquent cómo se llama realmente tu tabla
    protected $table = 'historial';
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['fk_alumno', 'fecha_apertura', 'observaciones'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function alumno()
    {
        return $this->belongsTo(\App\Models\Alumno::class, 'fk_alumno', 'no_control');
    }
    
}
