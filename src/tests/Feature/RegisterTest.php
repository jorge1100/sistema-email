<?php

namespace Tests\Feature;

use App\Mail\WelcomeUserMail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // La regla de validación es email:rfc,dns. En los tests se simulan las
        // consultas DNS para que el resultado no dependa de la red.
        Validator::fakeDnsLookups();
    }

    public function test_formulario_de_registro_se_muestra_correctamente(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertSee('Formulario de Registro');
        $response->assertSee('Nombre Completo');
        $response->assertSee('Correo Electrónico');
        $response->assertSee('Contraseña');
        $response->assertSee('Confirmar Contraseña');
        $response->assertSee('Registrarse');
    }

    public function test_campos_del_formulario_son_obligatorios(): void
    {
        $response = $this->post('/register', []);

        $response->assertSessionHasErrors(['name', 'email', 'password']);
        $this->assertDatabaseCount('users', 0);
    }

    public function test_nombre_debe_tener_minimo_3_caracteres(): void
    {
        $response = $this->post('/register', $this->datos(['name' => 'Jo']));

        $response->assertSessionHasErrors('name');
        $this->assertDatabaseCount('users', 0);
    }

    public function test_email_invalido_es_rechazado(): void
    {
        $response = $this->post('/register', $this->datos(['email' => 'correo-invalido']));

        $response->assertSessionHasErrors('email');
        $this->assertDatabaseCount('users', 0);
    }

    public function test_no_se_puede_registrar_un_email_repetido(): void
    {
        User::create([
            'name' => 'Usuario Existente',
            'email' => 'juan@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post('/register', $this->datos());

        $response->assertSessionHasErrors('email');
        $this->assertDatabaseCount('users', 1);
    }

    public function test_password_debe_tener_minimo_8_caracteres(): void
    {
        $response = $this->post('/register', $this->datos([
            'password' => 'corta',
            'password_confirmation' => 'corta',
        ]));

        $response->assertSessionHasErrors('password');
        $this->assertDatabaseCount('users', 0);
    }

    public function test_password_debe_estar_confirmada(): void
    {
        $response = $this->post('/register', $this->datos([
            'password_confirmation' => 'otra-contrasena',
        ]));

        $response->assertSessionHasErrors('password');
        $this->assertDatabaseCount('users', 0);
    }

    public function test_usuario_se_registra_y_el_correo_se_encola(): void
    {
        Mail::fake();

        $response = $this->post('/register', $this->datos());

        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'name' => 'Juan Pérez',
            'email' => 'juan@example.com',
        ]);

        // WelcomeUserMail implementa ShouldQueue: se encola en la tabla jobs.
        Mail::assertQueued(
            WelcomeUserMail::class,
            fn ($mail) => $mail->hasTo('juan@example.com')
        );
    }

    public function test_la_contrasena_se_guarda_hasheada(): void
    {
        Mail::fake();

        $this->post('/register', $this->datos());

        $user = User::where('email', 'juan@example.com')->firstOrFail();

        $this->assertNotSame('password123', $user->password);
        $this->assertTrue(Hash::check('password123', $user->password));
    }

    public function test_correo_de_bienvenida_usa_la_vista_y_los_datos_correctos(): void
    {
        $html = (new WelcomeUserMail([
            'name' => 'Juan Pérez',
            'email' => 'juan@example.com',
        ]))->render();

        $this->assertStringContainsString('¡Hola, Juan Pérez!', $html);
        $this->assertStringContainsString('Registro completado exitosamente', $html);
        $this->assertStringContainsString('juan@example.com', $html);
    }

    public function test_no_se_encola_correo_si_los_datos_son_invalidos(): void
    {
        Mail::fake();

        $this->post('/register', $this->datos(['email' => 'correo-invalido']));

        Mail::assertNothingQueued();
    }

    /**
     * Datos válidos del formulario, con la posibilidad de sobrescribir campos.
     *
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function datos(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Juan Pérez',
            'email' => 'juan@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ], $overrides);
    }
}
