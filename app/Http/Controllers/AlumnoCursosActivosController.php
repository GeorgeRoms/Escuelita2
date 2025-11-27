<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Inscripcione;

class AlumnoCursosActivosController extends Controller
{
    public function index()
    {
        $alumnoNoControl = Auth::user()->alumno_no_control;

        // Obtiene solo los cursos donde el alumno está inscrito
        $cursos = Inscripcione::with('curso')
            ->where('alumno_no_control', $alumnoNoControl)
            ->where('estado', 'Inscrito') // Asegúrate de la mayúscula exacta en tu BD
            ->get();


        return view('auth.cursos_activos', compact('cursos'));
    }
}