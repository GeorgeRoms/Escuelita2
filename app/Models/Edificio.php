<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\HasCustomPrimaryKey;

/**
 * Class Edificio
 *
 * @property $edificio
 * @property $salon
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Edificio extends Model
{

    use HasCustomPrimaryKey;

    protected $primaryKey = 'edificio';
    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['salon'];

    public function areas()
    {
        return $this->hasMany(\App\Models\Area::class, 'fk_edificio', 'id_edificio');
    }


}
