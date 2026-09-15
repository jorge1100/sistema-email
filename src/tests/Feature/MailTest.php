<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestBrevoMail;

class MailTest extends TestCase
{
    public function test_formulario_mail_se_muestra_correctamente(): void
    {
        $response = $this->get('/contacto');
        $response->assertStatus(200);
        $response->assertSee('Envío de correo');
        $response->assertSee('Correo destinatario');
        $response->assertSee('Asunto');
        $response->assertSee('Mensaje');
        $response->assertSee('Enviar correo');
    }

    public function test_campos_del_formulario_son_obligatorios(): void
    {
        $response = $this->post('/contacto', []);
        $response->assertSessionHasErrors([
            'email',
            'nombre',
            'asunto',
            'mensaje'
        ]);
    }

    public function test_destinatario_debe_ser_un_email_valido(): void
    {
        $response = $this->post('/contacto', [
            'email' => 'correo-invalido',
            'nombre' => 'Juan Pérez',
            'asunto' => 'Prueba',
            'mensaje' => 'Mensaje de prueba'
        ]);
        $response->assertSessionHasErrors('email');
    }

    public function test_correo_se_envia_correctamente(): void
    {
        Mail::fake();
        $this->post('/contacto', [
            'email' => 'alumno@example.com',
            'nombre' => 'Juan Pérez',
            'asunto' => 'Trabajo Práctico',
            'mensaje' => 'Trabajo recibido correctamente.'
        ]);
        Mail::assertSent(TestBrevoMail::class);
    }

    public function test_correo_se_envia_al_destinatario_correcto(): void
    {
        Mail::fake();
        $this->post('/contacto', [
            'email' => 'alumno@example.com',
            'nombre' => 'Juan Pérez',
            'asunto' => 'Trabajo Práctico',
            'mensaje' => 'Su trabajo fue recibido.'
        ]);
        Mail::assertSent(
            TestBrevoMail::class,
            function ($mail) {
                return $mail->hasTo('alumno@example.com');
            }
        );
    }

    public function test_mailable_recibe_los_datos_correctos(): void
    {
        Mail::fake();
        $this->post('/contacto', [
            'email' => 'alumno@example.com',
            'nombre' => 'María López',
            'asunto' => 'Aviso importante',
            'mensaje' => 'La clase comienza a las 14 horas.'
        ]);
        Mail::assertSent(
            TestBrevoMail::class,
            function ($mail) {
                return
                    $mail->nombre === 'María López' &&
                    $mail->asunto === 'Aviso importante' &&
                    $mail->mensaje === 'La clase comienza a las 14 horas.';
            }
        );
    }

    public function test_no_se_envia_correo_si_los_datos_son_invalidos(): void
    {
        Mail::fake();
        $this->post('/contacto', [
            'email' => 'correo-invalido',
            'nombre' => '',
            'asunto' => '',
            'mensaje' => ''
        ]);
        Mail::assertNothingSent();
    }

    public function test_telefono_no_puede_tener_menos_de_8_caracteres(): void
    {
        $response = $this->post('/contacto', [
            'email' => 'alumno@example.com',
            'nombre' => 'Juan Pérez',
            'telefono' => '123',
            'asunto' => 'Prueba',
            'mensaje' => 'Mensaje de prueba'
        ]);
        $response->assertSessionHasErrors('telefono');
    }

    public function test_telefono_valido_no_genera_error(): void
    {
        Mail::fake();
        $response = $this->post('/contacto', [
            'email' => 'alumno@example.com',
            'nombre' => 'Juan Pérez',
            'telefono' => '3704123456',
            'asunto' => 'Prueba telefono',
            'mensaje' => 'Mensaje de prueba'
        ]);
        $response->assertSessionHasNoErrors();
        Mail::assertSent(TestBrevoMail::class);
    }
}
