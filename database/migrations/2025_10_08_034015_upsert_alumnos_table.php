<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('alumnos')) {
            Schema::create('alumnos', function (Blueprint $table) {
                // PK NO autoincremental
                $table->string('no_control', 24)->primary();

                $table->string('nombre');
                $table->string('apellido_pat');
                $table->string('apellido_mat')->nullable();
                $table->enum('genero', ['M','F','O']);

                $table->unsignedBigInteger('fk_carrera');
                $table->smallInteger('anio');
                $table->tinyInteger('periodo');   // 1=Ene-Jun, 2=Ago-Dic, 3=Verano (ajusta)
                $table->integer('consecutivo')->nullable();

                $table->timestamps();

                $table->foreign('fk_carrera')
                      ->references('id_carrera')->on('carreras')
                      ->cascadeOnUpdate()->restrictOnDelete();
            });
        } else {
            // Si ya existe, añadimos columnas faltantes e índice único
            Schema::table('alumnos', function (Blueprint $table) {
                if (!Schema::hasColumn('alumnos', 'anio')) {
                    $table->smallInteger('anio')->after('genero');
                }
                if (!Schema::hasColumn('alumnos', 'periodo')) {
                    $table->tinyInteger('periodo')->after('anio');
                }
                if (!Schema::hasColumn('alumnos', 'consecutivo')) {
                    $table->integer('consecutivo')->nullable()->after('periodo');
                }
                if (!Schema::hasColumn('alumnos', 'no_control')) {
                    $table->string('no_control', 24)->nullable()->after('consecutivo');
                }
            });

            // Asegura índice único para poder referenciar no_control desde otras tablas
            if (!Schema::hasColumn('alumnos', 'no_control')) {
                // nada; ya se agregó arriba
            }
            // agrega unique si no existe
            try {
                Schema::table('alumnos', function (Blueprint $table) {
                    $table->unique('no_control', 'alumnos_no_control_unique');
                });
            } catch (\Throwable $e) {
                // ignora si ya existe el índice
            }

            // ⚠️ Si quieres que la PK de MySQL sea no_control (y NO 'id'),
            // necesitas SQL crudo y probablemente doctrine/dbal; lo podemos hacer después.
            // Por ahora basta con dejar 'id' como PK y 'no_control' UNIQUE,
            // y decirle a Eloquent que la PK lógica es no_control (modelo).
        }
    }

    public function down(): void
    {
        // No borres la tabla si ya existía antes; solo revierte adiciones.
        if (Schema::hasTable('alumnos')) {
            Schema::table('alumnos', function (Blueprint $table) {
                if (Schema::hasColumn('alumnos', 'no_control')) {
                    $table->dropUnique('alumnos_no_control_unique');
                    $table->dropColumn(['no_control', 'consecutivo', 'periodo', 'anio']);
                }
            });
        }
    }
};
