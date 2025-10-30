<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Agrega las nuevas combinaciones al listado ENUM.
     */
    public function up(): void
    {
        // 1. Define todos los valores, incluyendo los nuevos
        $newEnumValues = [
            'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado',
            'LUNES-MIERCOLES-VIERNES', 
            'MARTES-JUEVES-VIERNES', 
            'LUNES-MIERCOLES', 
            'MARTES-JUEVES'
        ];

        // 2. Convierte el array en la cadena SQL requerida: 'valor1', 'valor2', ...
        $enumString = "'" . implode("', '", $newEnumValues) . "'";
        
        // 3. Ejecuta la sentencia SQL para modificar la columna ENUM
        DB::statement("ALTER TABLE cursos MODIFY COLUMN dia_semana ENUM($enumString)");
    }

    /**
     * Reverse the migrations.
     * Vuelve a la lista original (solo días individuales).
     */
    public function down(): void
    {
        // 1. Lista original de valores ENUM
        $originalEnumValues = [
            'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'
        ];
        
        // 2. Convierte el array en la cadena SQL
        $enumString = "'" . implode("', '", $originalEnumValues) . "'";

        // 3. Ejecuta la sentencia SQL para revertir la columna
        DB::statement("ALTER TABLE cursos MODIFY COLUMN dia_semana ENUM($enumString)");
    }
};
