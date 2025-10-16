<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
        DROP PROCEDURE IF EXISTS resumen_especial_por_carrera;
        DELIMITER $$
        CREATE PROCEDURE resumen_especial_por_carrera(IN nombreCarrera VARCHAR(80))
        BEGIN
            /* RS1 */
            SELECT 
                c.nombre_carr AS carrera,
                COUNT(DISTINCT i.alumno_no_control) AS total_alumnos_especial
            FROM inscripciones i
            INNER JOIN alumno_carrera ac ON ac.alumno_no_control = i.alumno_no_control
            INNER JOIN carreras c ON c.id_carrera = ac.carrera_id
            WHERE i.intento = 'Especial'
              AND c.nombre_carr = nombreCarrera
            GROUP BY c.nombre_carr;

            /* RS2 */
            SELECT 
                m.id_materia,
                m.nombre_mat AS materia,
                COUNT(DISTINCT i.alumno_no_control) AS alumnos_en_especial
            FROM inscripciones i
            INNER JOIN cursos cu       ON cu.id_curso   = i.curso_id
            INNER JOIN materias m      ON m.id_materia  = cu.fk_materia
            INNER JOIN alumno_carrera ac ON ac.alumno_no_control = i.alumno_no_control
            INNER JOIN carreras c      ON c.id_carrera  = ac.carrera_id
            WHERE i.intento = 'Especial'
              AND c.nombre_carr = nombreCarrera
            GROUP BY m.id_materia, m.nombre_mat
            ORDER BY m.nombre_mat;

            /* RS3 */
            SELECT 
                COUNT(*) AS total_materias_en_especial
            FROM (
                SELECT m.id_materia
                FROM inscripciones i
                INNER JOIN cursos cu       ON cu.id_curso   = i.curso_id
                INNER JOIN materias m      ON m.id_materia  = cu.fk_materia
                INNER JOIN alumno_carrera ac ON ac.alumno_no_control = i.alumno_no_control
                INNER JOIN carreras c      ON c.id_carrera  = ac.carrera_id
                WHERE i.intento = 'Especial'
                  AND c.nombre_carr = nombreCarrera
                GROUP BY m.id_materia
            ) t;
        END $$
        DELIMITER ;
        SQL);
    }

    public function down(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS resumen_especial_por_carrera;');
    }
};

