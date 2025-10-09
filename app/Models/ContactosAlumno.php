<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\HasCustomPrimaryKey;

/**
 * Class ContactosAlumno
 *
 * @property $id_contacto
 * @property $correo
 * @property $telefono
 * @property $direccion
 * @property $created_at
 * @property $updated_at
 *
 * @property Alumno $alumno
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class ContactosAlumno extends Model
{

    protected $table = 'contactos_alumnos';
    protected $primaryKey = 'id_contacto';
    public $incrementing = true;       // funciona perfecto con AUTO_INCREMENT
    protected $keyType = 'int';

    protected $fillable = ['correo','telefono','direccion','fk_alumno'];

    public function alumno()
    {
        // alumnos.no_control es VARCHAR(24)
        return $this->belongsTo(\App\Models\Alumno::class, 'fk_alumno', 'no_control');
    }
    
}
