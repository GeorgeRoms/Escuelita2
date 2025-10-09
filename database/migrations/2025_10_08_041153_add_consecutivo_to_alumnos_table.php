<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('alumnos', function (Blueprint $table) {
            if (!Schema::hasColumn('alumnos', 'consecutivo')) {
                $table->unsignedInteger('consecutivo')->nullable()->after('periodo');
            }
            if (!Schema::hasColumn('alumnos', 'no_control')) {
                $table->string('no_control', 24)->nullable()->after('consecutivo');
                $table->unique('no_control', 'alumnos_no_control_unique');
            }
        });
    }

    public function down(): void
    {
        Schema::table('alumnos', function (Blueprint $table) {
            if (Schema::hasColumn('alumnos', 'no_control')) {
                $table->dropUnique('alumnos_no_control_unique');
                $table->dropColumn('no_control');
            }
            if (Schema::hasColumn('alumnos', 'consecutivo')) {
                $table->dropColumn('consecutivo');
            }
        });
    }
};
