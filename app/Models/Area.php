<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Area
 *
 * @property $id_area
 * @property $nombre_area
 * @property $fk_edificio
 * @property $fk_jefe
 * @property $created_at
 * @property $updated_at
 *
 * @property Edificio $edificio
 * @property Profesore $profesore
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Area extends Model
{
    
    protected $table = 'areas';
    protected $primaryKey = 'id_area';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = ['nombre_area', 'edificio_id', 'fk_jefe'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function edificio()
    {
        return $this->belongsTo(\App\Models\Edificio::class, 'edificio_id', 'id')
                ->withTrashed();
    }

    public function jefe()
    {
        return $this->belongsTo(\App\Models\Profesore::class, 'fk_jefe', 'id_profesor');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function profesore()
    {
        return $this->hasMany(\App\Models\Profesore::class, 'fk_area', 'id_area');
    }
    
}
