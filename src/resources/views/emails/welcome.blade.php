<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenida</title>
</head>

<body style="margin:0; padding:0; background-color:#f4f6f8; font-family: Arial, Helvetica, sans-serif;">
    <table role="presentation" style="width:100%; border-spacing:0; padding:40px 0;">
        <tr>
            <td align="center">
                <table role="presentation"
                    style="width:100%; max-width:600px; background:#ffffff; border-radius:8px; padding:32px; box-shadow:0 4px 12px rgba(0,0,0,0.05);">
                    <tr>
                        <td align="center">
                            <h2 style="margin:0 0 12px; color:#1e293b;">¡Hola, {{ $nombre }}! 👋</h2>
                            <p style="margin:0; color:#475569; font-size:16px; line-height:1.6;">
                                Gracias por registrarte. Tu cuenta ha sido creada con éxito.
                            </p>

                            <div
                                style="margin-top:20px; padding:12px; background:#e0f2fe; color:#0369a1; border-radius:6px; font-weight:bold;">
                                ✅ Registro completado exitosamente.
                            </div>

                            <p style="margin:24px 0 0; color:#64748b; font-size:14px; line-height:1.6;">
                                Ya podés ingresar a la plataforma con el correo
                                <strong style="color:#1e293b;">{{ $email }}</strong>.
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td align="center"
                            style="padding-top:30px; border-top:1px solid #e5e7eb; margin-top:24px; font-size:13px; color:#94a3b8;">
                            <p style="margin:20px 0 0;">
                                © {{ date('Y') }} {{ config('app.name') }} - Todos los derechos reservados.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
