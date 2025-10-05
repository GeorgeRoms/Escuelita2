<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\HasCustomPrimaryKey;

/**
 * Class Materia
 *
 * @property $id_materia
 * @property $nombre_mat
 * @property $creditos
 * @property $fk_cadena
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Materia extends Model
{

    use HasCustomPrimaryKey;

    protected $primaryKey = 'id_materia';
    public $incrementing = true;
    protected $keyType = 'int';
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['id_materia', 'nombre_mat', 'creditos', 'fk_cadena'];


}
