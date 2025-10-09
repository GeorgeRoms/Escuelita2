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

    protected $table = 'edificios';

    // Tu PK es 'edificio'
    protected $primaryKey = 'edificio';

    // Si NO es auto-incremental, deja esto en false (por tu DDL parece que no lo es)
    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = ['salon'];

    public function areas()
    {
        return $this->hasMany(\App\Models\Area::class, 'fk_edificio', 'id_edificio');
    }


}
