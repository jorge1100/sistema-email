<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;

Route::view('/', 'inicio')->name('inicio');
Route::view('/acerca-de', 'acerca')->name('acerca');

Route::get('/contacto', [ContactController::class, 'index'])->name('contacto');
Route::post('/contacto', [ContactController::class, 'enviar']);

// Alias para que /enviar-correo también funcione (coincide con nav activo)
Route::get('/enviar-correo', [ContactController::class, 'index']);