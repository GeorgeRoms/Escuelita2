<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TipoLoginController extends Controller
{
    public function login(Request $request)
    {
        $data = $request->validate([
            'email'    => ['required','email'],
            'password' => ['required'],
            'tipo'     => ['required','in:Administrador,Administrativo,Alumno,Profesor'],
        ]);

        $remember = $request->boolean('remember');
        $tipo     = $data['tipo'];

        // 1) Admin / Administrativo → autenticar contra users con role fijo
        if (in_array($tipo, ['Administrador','Administrativo'])) {
            $ok = Auth::attempt([
                'email' => $data['email'],
                'password' => $data['password'],
                'role' => $tipo,
            ], $remember);

            if (! $ok) {
                return back()->withErrors([
                    'email' => 'Credenciales inválidas para ' . $tipo,
                ])->withInput();
            }

            return $this->redirectPorRol(Auth::user());
        }

        // 2) Alumno → correo viene de contactos_alumnos
        if ($tipo === 'Alumno') {
            $contacto = DB::table('contactos_alumnos')
                ->where('correo', $data['email'])
                ->first();

            if (! $contacto) {
                return back()->withErrors([
                    'email' => 'Tu correo no está registrado en contactos de alumnos.',
                ])->withInput();
            }

            // Traer alumno para nombre/no_control
            $alumno = DB::table('alumnos')->where('no_control', $contacto->fk_alumno)->first();
            if (! $alumno) {
                return back()->withErrors([
                    'email' => 'No se encontró el alumno vinculado a ese correo.',
                ])->withInput();
            }

            // Crear/actualizar “users” espejo si no existe (auto-provisión)
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => trim($alumno->nombre.' '.$alumno->apellido_pat.' '.($alumno->apellido_mat ?? '')),
                    'password' => Hash::make($alumno->no_control), // contraseña temporal = No. de control
                    'role' => 'Alumno',
                    'alumno_no_control' => $alumno->no_control,
                ]
            );

            // Permite entrar con su password (si ya la cambió) o, primera vez, con No. de control
            $ok = Auth::attempt([
                'email'    => $user->email,
                'password' => $data['password'],
            ], $remember);

            if (! $ok) {
                return back()->withErrors([
                    'password' => 'Contraseña incorrecta. Si es tu primer acceso, usa tu No. de control como contraseña temporal.',
                ])->withInput();
            }

            // Verificamos que realmente sea Alumno:
            if ((Auth::user()->role ?? null) !== 'Alumno') {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Este correo no está habilitado como Alumno.',
                ]);
            }

            return $this->redirectPorRol(Auth::user());
        }

        // 3) (Opcional) Profesor → correo en contactos_profesores
        if ($tipo === 'Profesor') {
            $contacto = DB::table('contactos_profesores')
                ->where('correo', $data['email'])
                ->first();

            if (! $contacto) {
                return back()->withErrors([
                    'email' => 'Tu correo no está registrado en contactos de profesores.',
                ])->withInput();
            }

            $prof = DB::table('profesores')->where('id_profesor', $contacto->fk_profesor)->first();
            if (! $prof) {
                return back()->withErrors([
                    'email' => 'No se encontró el profesor vinculado a ese correo.',
                ])->withInput();
            }

            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => trim($prof->nombre.' '.$prof->apellido_pat.' '.($prof->apellido_mat ?? '')),
                    'password' => Hash::make('Profesor'.$contacto->fk_profesor), // temporal
                    'role' => 'Profesor',
                    'profesor_id' => $contacto->fk_profesor,
                ]
            );

            $ok = Auth::attempt([
                'email'    => $user->email,
                'password' => $data['password'],
            ], $remember);

            if (! $ok) {
                return back()->withErrors([
                    'password' => 'Contraseña incorrecta. Si es tu primer acceso, usa la temporal indicada por el sistema.',
                ])->withInput();
            }

            if ((Auth::user()->role ?? null) !== 'Profesor') {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Este correo no está habilitado como Profesor.',
                ]);
            }

            return $this->redirectPorRol(Auth::user());
        }

        // fallback
        return back()->withErrors(['tipo' => 'Tipo de usuario inválido'])->withInput();
    }

    protected function redirectPorRol(User $user)
    {
        switch ((string) $user->role) {
            case 'Administrador':  return redirect()->route('home.admini');   // auth.homeadmin
            case 'Administrativo': return redirect()->route('home.admin');  // home.blade.php
            case 'Alumno':         return redirect()->route('home.alumno');  // auth.homealumn
            case 'Profesor':       return redirect()->route('home.profesor'); // si lo habilitas
            default:               return redirect('/home'); // o '/'
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
