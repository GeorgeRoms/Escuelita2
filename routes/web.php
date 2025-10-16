<?php

use App\Http\Controllers\AlumnoCarreraController;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\AulaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarreraController;
use App\Http\Controllers\ContactosAlumnoController;
use App\Http\Controllers\ContactosProfesoreController;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\EdificioController;
use App\Http\Controllers\HistorialController;
use App\Http\Controllers\InscripcioneController;
use App\Http\Controllers\KardexController;
use App\Http\Controllers\MateriaController;
use App\Http\Controllers\PeriodoController;
use App\Http\Controllers\ProfesoreController;
use App\Support\Safe;
use App\Support\Responder;

Auth::routes();

Route::get('/', fn () => redirect()->route('home'));

// 3. Rutas Protegidas (El Middleware 'auth' es el candado)
// Ninguna de estas rutas será accesible sin iniciar sesión.
Route::middleware(['auth'])->group(function () {
    // Dashboard principal para usuarios logueados
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::resource('carreras', CarreraController::class);
Route::resource('alumnos', AlumnoController::class);
Route::resource('edificios', EdificioController::class);
Route::resource('materias', MateriaController::class);
Route::resource('profesores', ProfesoreController::class);
Route::resource('kardexes', KardexController::class);
Route::resource('historials', HistorialController::class);
Route::resource('cursos', CursoController::class);
Route::resource('contactos-alumnos', ContactosAlumnoController::class);
Route::resource('contactos-profesores', ContactosProfesoreController::class);
Route::resource('areas', AreaController::class);
Route::resource('periodos', PeriodoController::class);
Route::resource('inscripciones', InscripcioneController::class);
Route::resource('alumno-carreras', AlumnoCarreraController::class)
->parameters(['alumno-carreras' => 'alumno_carrerum']);
Route::resource('aulas', AulaController::class);
Route::view('/error/general', 'error.general')->name('error.general');

Route::get('/test/safe', function (\Illuminate\Http\Request $request) {
    return Safe::run(
        function () {
            // Excepción simulada dentro del bloque protegido (como en store/update)
            throw new \RuntimeException('Fallo simulado en Safe::run');
        },
        function () use ($request) {
            return Responder::ok($request, 'home', 'Todo bien'); // no llegará
        },
        function ($folio) use ($request) {
            return Responder::fail($request, $folio, 'error.general', 'Probando Safe::run');
        }
    );
});

});