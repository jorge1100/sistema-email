# TP — Registro de Usuarios y Mailer Brevo SMTP en Laravel 13

Implementación de la guía **"Registro de Usuarios y Mailer Brevo SMTP en Laravel 13"**
(Programación IV — Tecnicatura Universitaria en Programación) sobre el proyecto que ya
existía (`sistema-email`), respetando y sin romper nada de lo anterior.

---

## 1. Qué ya existía y se mantuvo igual

| Existente | Estado |
|---|---|
| Formulario de contacto (`ContactController` + `TestBrevoMail`) | Sin cambios, sigue funcionando |
| Layout y navbar (`layouts/app.blade.php`) | Se mantiene; solo se agregó el enlace **Registro** |
| Docker: nginx, php-fpm, MariaDB, Adminer, Mailpit, Mongo | Sin cambios |
| Migraciones (`users`, `cache`, `jobs`, `failed_jobs`) | Ya estaban migradas, no se tocaron |
| Tests de `MailTest` | Siguen pasando |

## 2. Qué se agregó (mapeo con la guía del TP)

| Sección de la guía | Archivo creado / modificado |
|---|---|
| 3. Configuración `.env` de Brevo | `src/.env`, `src/.env.example`, `configuracion email.txt` |
| 4. Clase Mailable | `src/app/Mail/WelcomeUserMail.php` |
| 5.1 Vista HTML del correo | `src/resources/views/emails/welcome.blade.php` |
| 5.2 Vista del formulario con errores y `old()` | `src/resources/views/auth/register.blade.php` |
| 6. Controlador con validaciones extra | `src/app/Http/Controllers/RegisterController.php` |
| 7. Rutas web | `src/routes/web.php` |
| 8. Pruebas del flujo | `src/tests/Feature/RegisterTest.php` |
| 9 y 10. Colas (Nivel 1, 2, 3 y 5) | `WelcomeUserMail implements ShouldQueue` + `QUEUE_CONNECTION=database` |
| 10.4 Nivel 4 (Job dedicado) | Documentado abajo como alternativa (no se implementó para no duplicar el envío) |

## 3. Flujo implementado

```
[Validar datos] → [Crear Usuario en BD] → [Encolar correo en tabla jobs] → [Responder al Navegador]
                                                     ↓
                                      php artisan queue:work  →  Brevo SMTP
```

- Validaciones: `required`, `string`, `min:3/max:255`, `email:rfc,dns`, `unique:users,email`,
  `min:8`, `confirmed`.
- La contraseña se guarda con `Hash::make()`.
- El correo de bienvenida **no se envía dentro del ciclo HTTP**: `WelcomeUserMail` implementa
  `ShouldQueue`, así que Laravel lo guarda en la tabla `jobs` y responde al instante.
- Reintentos: `$tries = 3` con `$backoff = [10, 30, 60]`. Si se agotan, el trabajo pasa a `failed_jobs`.

## 4. Cómo levantarlo y probarlo

```bash
# 1. Levantar los contenedores
docker compose up -d

# 2. (solo si hace falta) migrar
docker compose exec php php artisan migrate

# 3. Ver el sitio
#    App:      http://localhost:8091/register
#    Mailpit:  http://localhost:7653   (usuario: jorge / clave: 123456789)
#    Adminer:  http://localhost:8092

# 4. Worker de colas (dejarlo corriendo en otra terminal)
docker compose exec php php artisan queue:work

# 5. Probar: completar el formulario en /register
#    → se crea el usuario en MariaDB
#    → el correo de bienvenida aparece en Mailpit (o sale por Brevo)
```

> **Importante con la validación `email:rfc,dns`:** se exige que el dominio del correo tenga
> registros MX reales. Un dominio como `example.com` **tiene MX nulo** (RFC 7505), por lo que
> la validación lo rechaza. Para las pruebas usar un correo real, por ejemplo `alguien@gmail.com`.

## 5. Configuración de Brevo SMTP (sección 2 y 3 de la guía)

1. En Brevo: **Settings → Senders & Domains → Senders** y verificar el remitente.
2. En Brevo: **Settings → SMTP & API → SMTP → Generate a new SMTP key** (clave de 64 caracteres).
3. Editar `src/.env`: comentar el bloque de Mailpit y descomentar el bloque de Brevo:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp-relay.brevo.com
MAIL_PORT=587
MAIL_USERNAME=tu_correo_registrado_en_brevo@ejemplo.com
MAIL_PASSWORD=tu_smtp_key_de_64_caracteres
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=remitente_verificado@tudominio.com
MAIL_FROM_NAME="${APP_NAME}"
```

4. Limpiar la caché de configuración:

```bash
docker compose exec php php artisan config:clear
```

> `MAIL_PASSWORD` es la **SMTP Key de 64 caracteres**, no la API Key de Brevo.
> En local se deja Mailpit activo para no gastar créditos de Brevo.

## 6. Colas: niveles y comandos

| Nivel de la guía | Estado |
|---|---|
| **Nivel 1** — `ShouldQueue` en el Mailable | ✅ Implementado (`WelcomeUserMail`) |
| **Nivel 2** — `QUEUE_CONNECTION=database` | ✅ Ya estaba configurado en `.env` |
| **Nivel 3** — Worker `php artisan queue:work` | ✅ Verificado end-to-end |
| **Nivel 4** — Job dedicado | 📄 Alternativa documentada en el punto 7 |
| **Nivel 5** — Monitoreo de fallos | ✅ Tabla `failed_jobs` migrada |

```bash
# Procesar la cola
docker compose exec php php artisan queue:work

# Procesar solo lo pendiente y salir
docker compose exec php php artisan queue:work --once --stop-when-empty

# Trabajos fallidos (Nivel 5)
docker compose exec php php artisan queue:failed
docker compose exec php php artisan queue:retry {id}
docker compose exec php php artisan queue:retry all
```

## 7. Nivel 4 — Job dedicado (alternativa opcional)

La guía advierte que **no** se deben usar el Nivel 1 y el Nivel 4 al mismo tiempo, porque sería
redundante. Este proyecto usa el Nivel 1 (el recomendado para el caso simple). Si el docente pide
el Job dedicado, hay que hacer los tres cambios siguientes:

**a)** Quitar `implements ShouldQueue` de `WelcomeUserMail` (y el `use` de la interfaz).

**b)** Crear `src/app/Jobs/SendWelcomeEmailJob.php`:

```php
<?php

namespace App\Jobs;

use App\Mail\WelcomeUserMail;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendWelcomeEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public User $user)
    {
    }

    public function handle(): void
    {
        Mail::to($this->user->email)->send(new WelcomeUserMail([
            'name' => $this->user->name,
            'email' => $this->user->email,
        ]));
    }
}
```

**c)** En `RegisterController::store()`, reemplazar el `Mail::to(...)` por:

```php
use App\Jobs\SendWelcomeEmailJob;

SendWelcomeEmailJob::dispatch($user);
```

## 8. Tests

```bash
docker compose exec php php artisan test
# o en local:
cd src && php artisan test
```

- `tests/Feature/RegisterTest.php` (11 tests): formulario, campos obligatorios, nombre corto,
  email inválido, email repetido, contraseña corta, contraseña sin confirmar, registro exitoso
  con **correo encolado**, contraseña hasheada, render del correo y que no se encole nada si
  los datos son inválidos.
- `tests/Feature/MailTest.php` (9 tests): los del formulario de contacto, sin cambios.

En los tests la regla `email:rfc,dns` se resuelve con `Validator::fakeDnsLookups()` para no
depender de la red.

## 9. Verificación end-to-end realizada

```
GET  /register              → 200, muestra el formulario
POST /register (datos ok)   → 302 + "¡Usuario registrado con éxito!"
                              users: nuevo registro en MariaDB
                              jobs:  1 trabajo en cola (queue=default, attempts=0)
queue:work --once           → WelcomeUserMail DONE (103 ms)
                              jobs: 0 pendientes | failed_jobs: 0
Mailpit                     → correo "¡Bienvenido a nuestra plataforma!" recibido
```
