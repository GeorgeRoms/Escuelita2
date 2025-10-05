<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\HasCustomPrimaryKey;

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

    protected $primaryKey = 'id_curso';
    public $incrementing = true;
    protected $keyType = 'int';
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['id_curso', 'cupo', 'fk_materia', 'fk_profesor', 'fk_edificio'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function kardexes()
    {
        return $this->hasMany(\App\Models\Kardex::class, 'id_curso', 'fk_curso');
    }
    
}
