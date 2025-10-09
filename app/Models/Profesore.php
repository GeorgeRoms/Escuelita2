<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\HasCustomPrimaryKey;

/**
 * Class Profesore
 *
 * @property $id_profesor
 * @property $nombre
 * @property $apellido_pat
 * @property $apellido_mat
 * @property $area
 * @property $tipo
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Profesore extends Model
{

    use HasCustomPrimaryKey;

    protected $primaryKey = 'id_profesor';
    public $incrementing = true;
    protected $keyType = 'int';
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['nombre','apellido_pat','apellido_mat','fk_area','tipo'];

    public function area()
    {
        return $this->belongsTo(\App\Models\Area::class, 'fk_area', 'id_area');
    }


}
