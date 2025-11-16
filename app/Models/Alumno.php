<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\HasCustomPrimaryKey;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;
/**
 * Class Alumno
 *
 * @property $no_control
 * @property $nombre
 * @property $apellido_pat
 * @property $apellido_mat
 * @property $genero
 * @property $fk_carrera
 * @property $created_at
 * @property $updated_at
 *
 * @property Carrera $carrera
 * @property ContactosAlumno $contactosAlumno
 * @property Historial $historial
 * @property Kardex[] $kardexes
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Alumno extends Model
{
    use HasCustomPrimaryKey;
    use SoftDeletes;

    protected $table = 'alumnos';
    protected $primaryKey = 'no_control';
    public $incrementing = false;     // no_control no es AI
    protected $keyType = 'string';

    protected $perPage = 20;

    protected $fillable = [
        'nombre','apellido_pat','apellido_mat',
        'genero','carrera_id','anio','periodo', 'semestre'
    ];
    protected $guarded = ['no_control','consecutivo'];

    protected static function booted()
{
    static::creating(function (Alumno $a) {
        // Normaliza defaults si faltan (no pisa valores enviados)
        $a->anio    = $a->anio ?? now()->year;
        $a->periodo = $a->periodo ?? 1;

        // Si el no_control viene desde el form y cumple el patrón, respétalo y deriva campos
        if (!empty($a->no_control)) {
            // Formato esperado: YYYY P CCCC  (9 dígitos en total)
            if (preg_match('/^\d{9}$/', $a->no_control)) {
                $a->anio        = (int) substr($a->no_control, 0, 4);
                $a->periodo     = (int) substr($a->no_control, 4, 1);
                $a->consecutivo = (int) substr($a->no_control, 5, 4);
                return; // no generamos nada
            }
            // Si no cumple el patrón, puedes: (a) rechazar, o (b) ignorar y generar.
            // Aquí opto por generar para mantener integridad:
            $a->no_control = null;
        }

        // Si llegamos aquí, hay que generar no_control a partir de anio+periodo
        // Si ya mandaste consecutivo y está libre, úsalo; si no, calculamos el siguiente
        $siguiente = $a->consecutivo;

        DB::transaction(function () use ($a, &$siguiente) {
            if (empty($siguiente)) {
                // Busca el máximo consecutivo ya usado para {anio, periodo}
                $max = DB::table('alumnos')
                    ->where('anio', $a->anio)
                    ->where('periodo', $a->periodo)
                    ->lockForUpdate()
                    ->selectRaw('COALESCE(MAX(CAST(RIGHT(no_control, 4) AS UNSIGNED)), 0) as m')
                    ->value('m');

                $siguiente = (int) $max + 1;
            } else {
                // Si te mandaron consecutivo, asegúrate que no esté tomado
                $yaExiste = DB::table('alumnos')
                    ->where('anio', $a->anio)
                    ->where('periodo', $a->periodo)
                    ->whereRaw('RIGHT(no_control,4) = LPAD(?,4,"0")', [$siguiente])
                    ->lockForUpdate()
                    ->exists();
                if ($yaExiste) {
                    // Si está tomado, sube al siguiente libre
                    $max = DB::table('alumnos')
                        ->where('anio', $a->anio)
                        ->where('periodo', $a->periodo)
                        ->lockForUpdate()
                        ->selectRaw('COALESCE(MAX(CAST(RIGHT(no_control, 4) AS UNSIGNED)), 0) as m')
                        ->value('m');
                    $siguiente = (int) $max + 1;
                }
            }

            $a->consecutivo = $siguiente;
            $a->no_control  = sprintf('%04d%01d%04d', $a->anio, $a->periodo, $a->consecutivo);
        });
    });

    // En updates, NO recalcular a menos que tú cambies explícitamente esos campos
    static::updating(function (Alumno $a) {
        // Si cambias manualmente no_control, valida formato y rellena campos coherentes
        if ($a->isDirty('no_control')) {
            if (!preg_match('/^\d{9}$/', $a->no_control)) {
                throw new \Exception('no_control inválido. Formato esperado: YYYY P CCCC (9 dígitos).');
            }
            $a->anio        = (int) substr($a->no_control, 0, 4);
            $a->periodo     = (int) substr($a->no_control, 4, 1);
            $a->consecutivo = (int) substr($a->no_control, 5, 4);
        }
    });
}



    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    /** ===================== Relaciones ===================== */

    // 🔹 Carreras (pivot alumno_carrera) — nombre en plural
    public function carreras()
    {
        return $this->belongsToMany(Carrera::class, 'alumno_carrera',
            'alumno_no_control', // FK en pivot hacia alumnos
            'carrera_id'         // FK en pivot hacia carreras
        )->withPivot(['estatus','fecha_inicio','fecha_fin'])
         ->withTimestamps();
    }
    public function carrera()
    {
        return $this->carreras();
    }

    public function getNombreCompletoAttribute(): string
    {
        return trim("{$this->nombre} {$this->apellido_pat} " . ($this->apellido_mat ?? ''));
    }

    // (opcional) Etiqueta de género
    public function getGeneroLabelAttribute(): string
    {
        return $this->genero === 'M' ? 'Masculino' : ($this->genero === 'F' ? 'Femenino' : $this->genero);
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function contactosAlumno()
    {
        return $this->hasOne(ContactosAlumno::class, 'fk_alumno', 'no_control');
    }
    
    public function inscripciones()
    {
        return $this->hasMany(Inscripcione::class,'alumno_no_control','no_control');
    }

    public function getRouteKeyName(): string
    {
        return 'no_control';
    }

    public function contacto()
    {
        return $this->hasOne(\App\Models\ContactosAlumno::class, 'fk_alumno', 'no_control');
    }
    
}
