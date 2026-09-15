<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestBrevoMail;

class ContactController extends Controller
{
    public function index()
    {
        return view('contacto');
    }

    public function enviar(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'email' => 'required|email|max:255',
            'telefono' => 'nullable|string|min:8|max:20',
            'asunto' => 'required|string|max:255',
            'mensaje' => 'required|string|max:5000',
        ]);

        // Envía al correo destinatario ingresado (visible en Mailpit)
        Mail::to($request->email)->send(
            new TestBrevoMail(
                $request->nombre,
                $request->email,
                $request->asunto,
                $request->mensaje,
                $request->telefono
            )
        );

        return back()->with('success', 'Correo enviado correctamente a ' . $request->email);
    }
}