<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Aula
 *
 * @property $id
 * @property $edificio_id
 * @property $salon
 * @property $created_at
 * @property $updated_at
 *
 * @property Edificio $edificio
 * @property Area[] $areas
 * @property Curso[] $cursos
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Aula extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['edificio_id', 'salon'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function edificio()
    {
        return $this->belongsTo(\App\Models\Edificio::class, 'edificio_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function areas()
    {
        return $this->hasMany(\App\Models\Area::class, 'id', 'fk_edificio');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cursos()
    {
        return $this->hasMany(Curso::class,'aula_id');
    }
    
}
