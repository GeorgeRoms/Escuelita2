<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsignacionClase extends Model
{
    use HasFactory;

    /**
     * El nombre de la tabla asociada con el modelo.
     *
     * @var string
     */
    protected $table = 'asignaciones_clase'; // Tu tabla se llama así

    /**
     * Los atributos que se pueden asignar masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'profesor_id',
        'materia_id',
        'aula_id',
        'dia_semana',
        'hora_inicio',
        'hora_fin',
    ];

    // ... aquí van tus relaciones (belongsTo, etc.) ...
    
    public function profesor()
    {
        return $this->belongsTo(Profesore::class, 'profesor_id', 'id_profesor');
    }

    public function materia()
    {
        return $this->belongsTo(Materia::class, 'materia_id', 'id_materia');
    }

    public function aula()
    {
        return $this->belongsTo(Aula::class, 'aula_id', 'id');
    }
}