<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Añade la columna 'deleted_at' a la tabla 'asignaciones_clase'.
     */
    public function up(): void
    {
        Schema::table('asignaciones_clase', function (Blueprint $table) {
            // Agrega la columna 'deleted_at' necesaria para Soft Deletes
            $table->softDeletes(); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * Elimina la columna 'deleted_at' de la tabla 'asignaciones_clase'.
     */
    public function down(): void
    {
        Schema::table('asignaciones_clase', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
    