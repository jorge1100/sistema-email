<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeUserMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Datos del usuario recién registrado (name, email, ...).
     *
     * @var array<string, mixed>
     */
    public array $userData;

    /**
     * Cantidad de reintentos si el SMTP de Brevo falla o no responde.
     */
    public int $tries = 3;

    /**
     * Segundos de espera entre reintentos (backoff progresivo).
     *
     * @var array<int, int>
     */
    public array $backoff = [10, 30, 60];

    /**
     * @param  array<string, mixed>  $userData
     */
    public function __construct(array $userData)
    {
        $this->userData = $userData;
    }

    public function build(): self
    {
        return $this->subject('¡Bienvenido a nuestra plataforma!')
            ->view('emails.welcome')
            ->with([
                'nombre' => $this->userData['name'],
                'email' => $this->userData['email'] ?? '',
            ]);
    }
}
