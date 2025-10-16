<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request; 
use Illuminate\Http\JsonResponse; 
use Illuminate\Auth\Events\Registered; // Necesario para el evento de registro

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    /**
     * Sobreescribe el método 'register' del trait RegistersUsers.
     * Elimina la llamada a $this->guard()->login($user); para evitar
     * el inicio de sesión automático.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        // *** LÍNEA CLAVE ELIMINADA:
        // *** $this->guard()->login($user); 

        return $this->registered($request, $user)
                        ?: redirect($this->redirectPath());
    }

    /**
     * Maneja la respuesta después de que un usuario ha sido registrado.
     * Evita el inicio de sesión automático y redirige al login.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    protected function registered(Request $request, $user)
    {
        // Redirigir a la página de login con un mensaje de estado.
        return $request->wantsJson()
            ? new JsonResponse([], 201)
            : redirect('/login')->with('status', '¡Registro exitoso! Por favor, inicia sesión para continuar.');
    }
}
