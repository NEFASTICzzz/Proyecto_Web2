<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /*
     * AuthController.php - Creado por Dylan Sanabria, Dylan Cerda y Cristian Rojas
     * Maneja todo el flujo de usuarios: registro, login, perfil e historial de pedidos
     */

    // Muestra el formulario de registro de usuarios
    public function showRegister()
    {
        return view('auth.register');
    }

    // Procesa el registro de un nuevo usuario
    public function register(Request $request)
    {
        // Validamos que los datos vengan bien y no dejen nada vacio
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ], [
            'email.unique' => 'Mae, ese correo ya esta registrado en la tienda.',
            'password.confirmed' => 'Las contraseñas no coinciden, revisa bien.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
        ]);

        // Creamos el usuario en la base de datos con la clave cifrada
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Cifrado seguro bcrypt
            'phone' => $request->phone,
            'address' => $request->address,
            'role' => 'cliente', // Por defecto todos son clientes
        ]);

        // Iniciamos sesion automaticamente y mandamos al home
        Auth::login($user);

        return redirect()->route('home')->with('success', '¡Bienvenido a TechZone! Tu cuenta ha sido creada con éxito.');
    }

    // Muestra el formulario de inicio de sesion
    public function showLogin()
    {
        return view('auth.login');
    }

    // Procesa el login del usuario
    public function login(Request $request)
    {
        // Validacion rapida de credenciales
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Intentamos autenticar con Laravel Auth
        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate(); // Seguridad contra fijacion de sesion
            return redirect()->intended(route('home'))->with('success', '¡Hola de nuevo! Sesión iniciada correctamente.');
        }

        // Si se equivoco en la clave o correo
        return back()->withErrors([
            'email' => 'El correo o la contraseña son incorrectos. Revisa los datos.',
        ])->onlyInput('email');
    }

    // Cierra la sesion del usuario
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('info', 'Has cerrado sesión exitosamente. ¡Vuelve pronto!');
    }

    // Muestra el perfil del usuario autenticado y su historial de pedidos
    public function profile()
    {
        $user = Auth::user();
        // Traemos las ordenes del usuario ordenadas de la mas reciente a la mas vieja
        $orders = $user->orders()->with('items.product')->get();

        return view('profile.index', compact('user', 'orders'));
    }

    // Permite actualizar los datos personales del perfil
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:6|confirmed',
        ]);

        // Si quiere cambiar contraseña, revisamos la clave actual
        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'La contraseña actual no es correcta.']);
            }
            $user->password = Hash::make($request->new_password);
        }

        // Actualizamos nombre, telefono y direccion
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->save();

        return back()->with('success', 'Tus datos personales han sido mejoardos e informados correctamente.');
    }
}
