<?php

namespace App\Http\Controllers;

use App\Mail\WelcomeUserMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    /**
     * Muestra el formulario de registro.
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Valida, guarda el usuario y encola el correo de bienvenida.
     */
    public function store(Request $request)
    {
        // 1. Validaciones extra completas
        //    - tipo de dato y tamaño mínimo/máximo
        //    - formato RFC y verificación de dominio (DNS)
        //    - unicidad del correo en la tabla users
        //    - confirmación obligatoria de contraseña (password_confirmation)
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'email' => ['required', 'string', 'email:rfc,dns', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'name.required' => 'El nombre completo es obligatorio.',
            'name.min' => 'El nombre debe tener al menos 3 caracteres.',
            'name.max' => 'El nombre no puede superar los 255 caracteres.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Ingresá un correo válido: debe respetar el formato RFC y tener un dominio existente.',
            'email.unique' => 'Este correo electrónico ya se encuentra registrado.',
            'email.max' => 'El correo no puede superar los 255 caracteres.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas ingresadas no coinciden.',
        ]);

        // 2. Guardar usuario en la Base de Datos
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // 3. Enviar correo de bienvenida mediante Brevo SMTP.
        //    WelcomeUserMail implementa ShouldQueue (Nivel 1): el envío se
        //    guarda en la tabla jobs y lo procesa el worker (php artisan queue:work).
        Mail::to($user->email)->send(new WelcomeUserMail($validated));

        // 4. Retornar respuesta exitosa
        return back()->with('success', '¡Usuario registrado con éxito! Te enviamos un correo de bienvenida.');
    }
}
