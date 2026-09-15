<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TestBrevoMail extends Mailable
{
    use Queueable, SerializesModels;

    public $nombre;
    public $email;
    public $asunto;
    public $mensaje;
    public $telefono;

    public function __construct($nombre, $email, $asunto, $mensaje, $telefono = null)
    {
        $this->nombre = $nombre;
        $this->email = $email;
        $this->asunto = $asunto;
        $this->mensaje = $mensaje;
        $this->telefono = $telefono;
    }

    public function build()
    {
        return $this
            ->subject($this->asunto ?: 'Nueva consulta desde el sitio web')
            ->view('emails.test-brevo');
    }
}