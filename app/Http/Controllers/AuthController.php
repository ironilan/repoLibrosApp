<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{

    public function viewLogin()
    {
        return view('auth.login');
    }




    /**
     * Registro de usuario
     */
    public function register(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'tipo_doc' => 'required',
            'num_doc' => 'required',
            'celular' => 'required',
        ]);



        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'tipo_doc' => $request->tipo_doc,
            'num_doc' => $request->num_doc,
            'celular' => $request->celular,
            'password' => Hash::make($request->password),
        ]);





        $token = JWTAuth::fromUser($user);

        return response()->json([
            'message' => 'Usuario registrado con éxito',
            'user' => $user,
            'token' => $token
        ], 201);
    }

    /**
     * Inicio de sesión
     */
    public function loginJWT(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (!$token = Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales no coinciden con nuestros registros.'],
            ]);
        }

        return $this->respondWithToken($token);
    }


    public function login(Request $request)
    {
        // Validar los datos de entrada
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Verificar si el usuario existe y tiene estado 1
        $user = User::where('email', $credentials['email'])->first();

        if (!$user || $user->estado != 1) {
            throw ValidationException::withMessages([
                'email' => ['Tu cuenta está inactiva. Contacta al administrador.'],
            ]);
        }

        // Intentar autenticar al usuario
        if (!Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales no coinciden con nuestros registros.'],
            ]);
        }

        // Regenerar la sesión para evitar ataques de fijación de sesión
        $request->session()->regenerate();

        return redirect()->intended('/dashboard'); // Redirige al usuario a su página principal
    }




    /**
     * Cerrar sesión
     */
    public function logout()
    {
        Auth::logout();
        return response()->json(['message' => 'Sesión cerrada correctamente']);
    }

    /**
     * Obtener usuario autenticado
     */
    public function me()
    {
        return response()->json(Auth::user());
    }

    /**
     * Refrescar token
     */
    public function refresh()
    {
        return $this->respondWithToken(Auth::refresh());
    }

    /**
     * Formato de respuesta con token
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60,
            'user' => [
                'id' => Auth::user()->id,
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'role' => Auth::user()->getRoleNames()->first() // Obtiene el primer rol del usuario
            ]
        ]);
    }
}
