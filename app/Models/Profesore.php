<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\HasCustomPrimaryKey; // Mantengo tu use statement

/**
 * Class Profesore
 *
 * @property $id_profesor
 * @property $nombre
 * @property $apellido_pat
 * @property $apellido_mat
 * @property $fk_area
 * @property $tipo
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Profesore extends Model
{
    // PROPIEDAD DE VALIDACIÓN (Añadida para resolver el error "Access to undeclared static property")
    // public static $rules = [
    //     'nombre'       => 'required|string|max:100',
    //     'apellido_pat' => 'required|string|max:100',
    //     'apellido_mat' => 'required|string|max:100',
    //     // Uso 'Area::class' basado en tu método 'area()', asumiendo que este es el modelo correcto para la tabla catal_areas
    //     'fk_area'      => 'required|integer|exists:areas,id_area', 
    //     'tipo'         => 'required|in:Tiempo completo,Medio Tiempo,Asignatura',
    // ];

    use HasCustomPrimaryKey;

    protected $table = 'profesores'; // Añadido para claridad, aunque es implícito
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
        // Uso \App\Models\Area::class según tu relación. Si tu tabla de áreas se llama 'catal_areas',
        // Asegúrate de que el modelo 'Area' o 'CatalArea' haga referencia a esa tabla.
        return $this->belongsTo(\App\Models\Area::class, 'fk_area', 'id_area');
    }

    public function contacto()
    {
        return $this->hasOne(\App\Models\ContactosProfesore::class, 'fk_profesor', 'id_profesor');
    }



}
