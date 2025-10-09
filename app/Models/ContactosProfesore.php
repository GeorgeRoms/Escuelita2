<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\HasCustomPrimaryKey;

/**
 * Class ContactosProfesore
 *
 * @property $id_contacto
 * @property $correo
 * @property $telefono
 * @property $direccion
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class ContactosProfesore extends Model
{

    protected $table = 'contactos_profesores';
    protected $primaryKey = 'id_contacto';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = ['correo', 'telefono', 'direccion', 'fk_profesor'];


    public function profesor()
    {
        // Tu modelo de profesor es "Profesore"
        return $this->belongsTo(\App\Models\Profesore::class, 'fk_profesor', 'id_profesor');
    }

}
