<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Periodo
 *
 * @property $id
 * @property $anio
 * @property $nombre
 * @property $created_at
 * @property $updated_at
 *
 * @property Curso[] $cursos
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Periodo extends Model
{
    
    use SoftDeletes;

    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'periodos';
    protected $fillable = ['anio', 'nombre'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function scopeAnioActual($query)
    {
        return $query->where('anio', now()->year);
    }

    public function cursos()
    {
        return $this->hasMany(Curso::class,'periodo_id');
    }
    
}
