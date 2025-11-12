<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $u = Auth::user();
        if (!$u) return redirect()->route('login');

        switch ((string) $u->role) {
            case 'Administrador':  return redirect()->route('home.admini');   // auth.homeadmin
            // case 'Administrativo': return redirect()->route('home.admin');  // home.blade.php
            case 'Alumno':         return redirect()->route('home.alumno');
            case 'Profesor':       return redirect()->route('home.profesor');
            default:               return view('home'); // o donde prefieras
        }
    }
}
