<?php


use App\Http\Controllers\AlumnoCarreraController;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\AsignacionClaseController; // ¡Importación añadida!
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
use App\Http\Controllers\ReportesController;
use App\Http\Controllers\ReporteEspecialController;
use App\Http\Controllers\ReporteCursoController;
use App\Http\Controllers\ReporteProfesorController;
use App\Http\Controllers\ReporteAlumnoController;
use App\Http\Controllers\ReporteCarreraPeriodoController;
use App\Http\Controllers\ReporteTopAlumnosController;
use App\Http\Controllers\Auth\TipoLoginController;
use App\Http\Controllers\AlumnoHomeController;
use App\Http\Controllers\ProfesorHomeController;

// 🔐 Reemplaza el POST /login para que use TipoLoginController
Route::post('/login/tipo', [TipoLoginController::class, 'login'])->name('login.tipo');


Auth::routes();

Route::get('/', fn () => redirect()->route('home'));

// ----------------------------------------------------------------------
// ✨ RUTAS PÚBLICAS DE SIMULACIÓN (Para botones de Login) ✨
// ----------------------------------------------------------------------
Route::get('/panel/superusuario', function () {
    // CAMBIO: Ahora carga la vista de login (auth.login) como solicitaste.
    return view('auth.login'); 
})->name('panel.superusuario');

Route::get('/panel/administrador', function () {
    return view('auth.homeadmin'); 
})->name('panel.administrador');

Route::get('/panel/alumno', function () {
    return redirect()->route('alumno.home');
})->name('panel.alumno');

Route::get('/panel/profesor', function () {
    return redirect()->route('home.profesor');
})->name('panel.profesor');
// ----------------------------------------------------------------------


// 3. Rutas Protegidas (El Middleware 'auth' es el candado)
// Ninguna de estas rutas será accesible sin iniciar sesión.
Route::middleware(['auth'])->group(function () {

    // Route::get('/admin/home',   fn () => view('auth.homeadmin'))->name('home.admin');   // Admin
    Route::get('/staff/home',   fn () => view('home'))->name('home.admini');        // Administrativo (tu home.blade.php)
    Route::get('/profe/home', [ProfesorHomeController::class, 'index'])
    ->name('home.profesor');
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

// 1) Primero el endpoint AJAX
Route::prefix('inscripciones')->name('inscripciones.')->group(function () {
    Route::get('intento', [InscripcioneController::class, 'intento'])->name('intento');
});
// 2) Luego el resource (opcional: limitar el parámetro a números)
Route::resource('inscripciones', InscripcioneController::class)
    ->where(['inscripcione' => '[0-9]+']);

// RUTA AÑADIDA PARA LAS ASIGNACIONES DE CLASE (HORARIOS)
Route::resource('asignaciones', AsignacionClaseController::class);

Route::view('/error/general', 'error.general')->name('error.general');

//RUTAS VISTAS ALUMNOS
Route::get('/alumno/home',         [AlumnoHomeController::class, 'index'])->name('alumno.home');
    Route::get('/alumno/kardex',       [AlumnoHomeController::class, 'kardex'])->name('alumno.kardex');
    Route::get('/alumno/horarios',     [AlumnoHomeController::class, 'horarios'])->name('alumno.horarios');
    Route::get('/alumno/calificaciones',[AlumnoHomeController::class, 'calificaciones'])->name('alumno.calificaciones');
    Route::get('/alumno/datos',        [AlumnoHomeController::class, 'datos'])->name('alumno.datos_personales');
    Route::get('/alumno/cursos-activos',[AlumnoHomeController::class, 'cursosActivos'])->name('alumno.cursos_activos');
    Route::get('/alumno/plan-materias',[AlumnoHomeController::class, 'planMaterias'])->name('alumno.plan_materias');

// Grupo de rutas del profesor
Route::prefix('profesor')->name('profesor.')->group(function () {
    Route::get('cursos-vigentes',  [ProfesorHomeController::class, 'cursosVigentes'])->name('cursos.vigentes');
    Route::get('cursos-historial',[ProfesorHomeController::class, 'cursosHistorial'])->name('cursos.historial');
    Route::get('datos', [ProfesorHomeController::class, 'datosPersonales'])->name('datos_personales');

    Route::get('curso/{curso}/lista',           [ProfesorHomeController::class, 'listaAlumnos'])->name('curso.lista');
    Route::get('curso/{curso}/calificaciones',  [ProfesorHomeController::class, 'formCalificaciones'])->name('curso.calificaciones');
    Route::post('curso/{curso}/calificaciones', [ProfesorHomeController::class, 'guardarCalificaciones'])->name('curso.calificaciones.guardar');
});

// Grupo de Rutas de Reportes
Route::prefix('reportes')->group(function () {
    // Principal
    Route::get('/', [ReportesController::class, 'index'])->name('reportes.index');

    // ** RUTA AJAX PARA FILTRO DINÁMICO (PROFESOR POR ÁREA) **
    // Mapea la URL /reportes/profesores/por_area/{area_id} al método getProfesoresPorArea
    Route::get('profesores/por_area/{area_id}', [ProfesoreController::class, 'getProfesoresPorArea'])
        ->name('reportes.profesores.por_area');
    
    // Rutas de Vistas
    Route::get('/especial', [ReporteEspecialController::class, 'resumenPorCarrera'])->name('reportes.especial');
    Route::get('/curso', [ReporteCursoController::class, 'index'])->name('reportes.curso.index');
    Route::get('/curso/ver', [ReporteCursoController::class, 'ver'])->name('reportes.curso.ver');
    Route::get('/profesor/ver', [ReporteProfesorController::class, 'ver'])->name('reportes.profesor.ver');
    Route::get('/alumno/ver', [ReporteAlumnoController::class, 'ver'])->name('reportes.alumno.ver');
    Route::get('/carrera-periodo/ver', [ReporteCarreraPeriodoController::class, 'ver'])->name('reportes.carrera_periodo.ver');
    Route::get('/top-alumnos/ver', 	[ReporteTopAlumnosController::class, 'ver'])->name('reportes.top_alumnos.ver');

    // Rutas de PDF
    Route::get('/especial/pdf', [ReporteEspecialController::class, 'especialPdf'])
        ->name('reportes.especial.pdf');
    Route::get('/curso/pdf', [ReporteCursoController::class, 'cursoPdf'])
        ->name('reportes.curso.pdf');
    Route::get('/profesor/pdf', [ReporteProfesorController::class, 'materiasProfesorPdf'])
        ->name('reportes.profesor.pdf');
    Route::get('/historial/pdf', [ReporteAlumnoController::class, 'historialPdf'])
        ->name('reportes.historial.pdf');
    Route::get('/carrera-periodo/pdf',[ReporteCarreraPeriodoController::class, 'pdf'])
        ->name('reportes.carrera_periodo.pdf');
    Route::get('/top-alumnos/pdf', 	[ReporteTopAlumnosController::class, 'pdf'])
        ->name('reportes.top_alumnos.pdf');
}); // Fin del grupo de reportes

Route::get('/api/areas/{area}/profesores', [ReporteProfesorController::class, 'profesoresPorArea'])
    ->name('api.areas.profesores');



// routes/web.php
Route::get('/_debug', function () {
    return [
        'app_env' 	 => app()->environment(),
        'app_debug' => config('app.debug'),
        'env_debug' => env('APP_DEBUG'), // ojo: solo para diagnosticar
    ];
});


Route::get('/_boom', function () {
    throw new \Exception('Boom test');
});



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