# Guía de configuración desde cero — `sistema-email`

Guía paso a paso para clonar y dejar funcionando **desde cero** este proyecto
(Laravel 13 + Nginx + PHP-FPM + MariaDB + Mailpit + MongoDB, todo con Docker).

---

## 0. Requisitos previos

| Herramienta | Versión usada en el proyecto | Verificar con |
|---|---|---|
| Docker | 29.x | `docker --version` |
| Docker Compose | v5.x | `docker compose version` |
| Git | cualquiera | `git --version` |

> No hace falta tener PHP, Composer ni Node instalados en la máquina:
> todo corre dentro de los contenedores. Solo se necesitan para desarrollo local
> por fuera de Docker (opcional).

---

## 1. Clonar el repositorio

```bash
git clone https://github.com/jorge1100/sistema-email.git
cd sistema-email
```

Estructura de carpetas:

```
sistema-email/
├── docker-compose.yml      # Servicios: php, nginx, mailpit, mariadb, adminer, mongodb, mongo-express
├── Dockerfile              # Imagen PHP 8.5-FPM + extensiones + Composer
├── nginx/default.conf      # Config de Nginx (puertos 80 y 443)
├── ssl/                    # Certificado autofirmado (server.crt / server.key)
├── src/                    # Aplicación Laravel 13
│   ├── .env.example        # Plantilla de variables de entorno
│   └── ...
├── configuracion email.txt # Apuntes rápidos de DB / mail / colas
├── GUIA_TP_BREVO.md        # Guía del TP (registro + Brevo + colas)
└── GUIA_CONFIGURACION.md   # Este archivo
```

---

## 2. Crear el archivo `.env`

El `.env` **no se versiona** (está en `.gitignore`), así que hay que crearlo a partir
de la plantilla. El `.env.example` trae por defecto Mailpit activo y Brevo comentado.

```bash
cp src/.env.example src/.env
```

Luego editá `src/.env` con estos valores para Docker (ya son los que espera el
`docker-compose.yml`):

```env
APP_NAME=Laravel
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8091

# ── Base de datos (MariaDB del docker-compose) ──
DB_CONNECTION=mysql
DB_HOST=mariadb
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=jorge
DB_PASSWORD=123456789

# ── Sesión / caché / colas ──
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database

# ── Correo LOCAL (Mailpit) ──
MAIL_MAILER=smtp
MAIL_SCHEME=null
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_ENCRYPTION=null
MAIL_USERNAME=jorge
MAIL_PASSWORD=123456789
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

> **Ojo:** el `MAIL_HOST` y `DB_HOST` apuntan a los **nombres de los servicios**
> (`mailpit`, `mariadb`), no a `localhost`. Es correcto: dentro de la red de Docker
> se resuelven por nombre. Desde tu navegador, en cambio, usás `localhost`.

---

## 3. Levantar los contenedores

```bash
docker compose up -d --build
```

Esto construye la imagen PHP (tarda la primera vez porque instala extensiones) y
levanta los 7 servicios:

| Servicio | Contenedor | Puerto host → contenedor | Para qué |
|---|---|---|---|
| `nginx` | `sistema-email_nginx` | `8091 → 80`, `8441 → 443` | Servidor web (entrada de la app) |
| `php` | `sistema-email_php` | interno `9000` | PHP-FPM 8.5 |
| `mariadb` | `sistema-email_mariadb` | `3306` interno | Base de datos principal |
| `adminer` | `sistema-email_adminer` | `8092 → 8080` | Cliente web de MySQL |
| `mailpit` | `mailpit` | `9432 → 1025`, `7653 → 8025` | Captura de correos en local |
| `mongodb` | `sistema-email_mongodb` | interno `27017` | Base NoSQL |
| `mongo-express` | `sistema-email_mongoexpress` | `8093 → 8081` | Cliente web de Mongo |

Verificar que estén arriba:

```bash
docker compose ps
```

---

## 4. Instalar dependencias de Composer

En un clon nuevo la carpeta `src/vendor/` no existe. Instalala dentro del contenedor:

```bash
docker compose exec php composer install
```

> Si el contenedor `php` no tuviera network, o querés forzarlo:
> `docker compose exec php composer install --no-interaction --prefer-dist`

---

## 5. Generar la clave de la aplicación

`APP_KEY` es obligatoria para que Laravel encripte sesiones y cookies:

```bash
docker compose exec php php artisan key:generate
```

(Esto escribe el valor en `src/.env`.)

---

## 6. Migrar la base de datos

Crea las tablas `users`, `password_reset_tokens`, `sessions`, `cache`, `jobs` y
`failed_jobs` en MariaDB:

```bash
docker compose exec php php artisan migrate
```

Comprobar que quedaron conectados:

```bash
docker compose exec mariadb mariadb -ujorge -p123456789 -e "SHOW TABLES;" laravel
```

---

## 7. Abrir la aplicación

| Recurso | URL | Credenciales |
|---|---|---|
| App (inicio) | http://localhost:8091/ | — |
| Registro | http://localhost:8091/register | — |
| Contacto | http://localhost:8091/contacto | — |
| Mailpit (correos locales) | http://localhost:7653 | `jorge` / `123456789` |
| Adminer (MySQL) | http://localhost:8092 | Servidor `mariadb`, user `jorge`, pass `123456789` |
| Mongo Express | http://localhost:8093 | user `jorge`, pass `123456789` |
| HTTPS (cert autofirmado) | https://localhost:8441/ | El navegador avisará que el certificado no es de confianza |

---

## 8. Correr el worker de colas (obligatorio)

El correo de bienvenida (`WelcomeUserMail`) implementa `ShouldQueue`: **no se envía
durante el pedido HTTP**, se guarda en la tabla `jobs` y lo procesa un worker.
Sin el worker corriendo, el correo queda pendiente para siempre.

```bash
docker compose exec php php artisan queue:work
```

Dejalo corriendo en una terminal aparte mientras probás el registro.

---

## 9. Probar el flujo completo

1. Abrí http://localhost:8091/register
2. Completá nombre, correo y contraseña (mínimo 8 caracteres, confirmada).
3. Al enviar: se crea el usuario en MariaDB y el correo se **encola**.
4. El worker lo procesa y el correo aparece en http://localhost:7653 (Mailpit).

> **Importante con `email:rfc,dns`:** la validación exige que el dominio tenga
> registros MX reales. Un correo como `algo@example.com` **es rechazado**.
> Para probar usá un dominio real (por ejemplo `alguien@gmail.com`).

---

## 10. Configurar envío real con Brevo SMTP (opcional)

En local se usa Mailpit para no gastar créditos. Para enviar correos de verdad:

1. En Brevo: **Settings → Senders & Domains** y verificá el remitente.
2. En Brevo: **Settings → SMTP & API → SMTP → Generate a new SMTP key**
   (clave de **64 caracteres**; NO es la API Key).
3. En `src/.env`, comentá el bloque de Mailpit y descomentá:

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

Reiniciá el worker de colas para que tome la nueva configuración.

---

## 11. Correr los tests

```bash
docker compose exec php php artisan test
```

Los tests usan SQLite en memoria, `MAIL_MAILER=array` y `QUEUE_CONNECTION=sync`
(ver `src/phpunit.xml`), así que no tocan MariaDB ni la red.

---

## 12. Comandos útiles del día a día

```bash
# Ver logs
docker compose logs -f php
docker compose logs -f nginx

# Entrar a una shell del contenedor PHP
docker compose exec php bash

# Artisan directo
docker compose exec php php artisan migrate
docker compose exec php php artisan config:clear
docker compose exec php php artisan cache:clear

# Colas: ver fallidos y reintentar
docker compose exec php php artisan queue:failed
docker compose exec php php artisan queue:retry all

# Procesar solo lo pendiente y salir
docker compose exec php php artisan queue:work --once --stop-when-empty

# Apagar (conserva datos) / apagar borrando volúmenes (borra DB y Mongo)
docker compose down
docker compose down -v
```

---

## 13. Problemas comunes

| Síntoma | Causa / Solución |
|---|---|
| `No application encryption key` | Falta `php artisan key:generate` (paso 5). |
| `Class ... not found` / `vendor/autoload.php` no existe | Falta `composer install` (paso 4). |
| `SQLSTATE[HY000] [2002]` al migrar | MariaDB todavía no está listo. Esperá unos segundos y reintentá; o `docker compose restart php`. |
| El registro no envía correo | El worker de colas no está corriendo (paso 8). |
| `email:rfc,dns` rechaza el correo | El dominio no tiene MX reales. Usá un correo real para probar. |
| Los cambios en `.env` no se aplican | Ejecutá `php artisan config:clear` y reiniciá el worker. |
| El navegador marca certificado inválido en HTTPS | Es un certificado autofirmado; aceptá la excepción o usá HTTP `:8091`. |

---

## 14. Regenerar el certificado SSL autofirmado (si hace falta)

Si el certificado `ssl/server.crt` venció o querés rehacerlo:

```bash
openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
  -keyout ssl/server.key -out ssl/server.crt \
  -subj "/CN=localhost"
docker compose restart nginx
```

---

## 15. Resumen mínimo (camino feliz)

```bash
git clone https://github.com/jorge1100/sistema-email.git
cd sistema-email
cp src/.env.example src/.env
docker compose up -d --build
docker compose exec php composer install
docker compose exec php php artisan key:generate
docker compose exec php php artisan migrate
docker compose exec php php artisan queue:work   # dejar en otra terminal
# Abrir http://localhost:8091/register
```
