<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\RegisterController;

Route::view('/', 'inicio')->name('inicio');
Route::view('/acerca-de', 'acerca')->name('acerca');

// Rutas para el flujo de registro de usuarios (TP Laravel 13 + Brevo SMTP + Colas)
Route::get('/register', [RegisterController::class, 'create'])->name('register.create');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

Route::get('/contacto', [ContactController::class, 'index'])->name('contacto');
Route::post('/contacto', [ContactController::class, 'enviar']);

// Alias para que /enviar-correo también funcione (coincide con nav activo)
Route::get('/enviar-correo', [ContactController::class, 'index']);