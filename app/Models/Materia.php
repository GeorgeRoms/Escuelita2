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

    protected $primaryKey = 'id_materia';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = ['nombre_mat','creditos','fk_cadena'];

    // Padre (prerrequisito inmediato)
    public function prerrequisito()
    {
        return $this->belongsTo(self::class, 'fk_cadena', 'id_materia');
    }

    // Hijas (materias que dependen de esta)
    public function seriadas()
    {
        return $this->hasMany(self::class, 'fk_cadena', 'id_materia');
    }

    // Cadena de prerrequisitos hacia arriba (A <- B <- C)
    public function cadenaHaciaArriba(): \Illuminate\Support\Collection
    {
        $out = collect();
        $m = $this->prerrequisito;
        while ($m) {
            $out->push($m);
            $m = $m->prerrequisito;
        }
        return $out; // [inmediato, abuelo, ...]
    }


}
