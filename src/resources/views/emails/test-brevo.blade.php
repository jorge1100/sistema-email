<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Nueva Consulta</title>
</head>

<body style="margin:0; padding:0; background-color:#f3f4f6; font-family:Arial, Helvetica, sans-serif;">

    <table role="presentation" style="width:100%; border-spacing:0; padding:40px 0;">
        <tr>
            <td align="center">

                <table role="presentation"
                    style="width:100%; max-width:600px; background:#ffffff; border-radius:8px; padding:30px; box-shadow:0 2px 8px rgba(0,0,0,0.1);">

                    <tr>
                        <td align="center" style="padding-bottom:20px;">
                            <h2 style="margin:0; font-size:24px; color:#111827;">
                                📩 Nueva Consulta desde el Sitio Web
                            </h2>
                        </td>
                    </tr>

                    <tr>
                        <td style="font-size:16px; color:#374151; line-height:1.6;">

                            <p>
                                Se recibió una nueva consulta desde el formulario web.
                            </p>

                            <hr style="border:none; border-top:1px solid #e5e7eb; margin:20px 0;">

                            <p>
                                <strong>Nombre:</strong><br>
                                {{ $nombre }}
                            </p>

                            <p>
                                <strong>Email:</strong><br>
                                {{ $email }}
                            </p>

                            @if(!empty($telefono))
                            <p>
                                <strong>Teléfono:</strong><br>
                                {{ $telefono }}
                            </p>
                            @endif

                            <p>
                                <strong>Asunto:</strong><br>
                                {{ $asunto }}
                            </p>

                            <p>
                                <strong>Mensaje:</strong>
                            </p>

                            <div style="
                                background:#f9fafb;
                                border-left:4px solid #2563eb;
                                padding:15px;
                                border-radius:4px;
                            ">
                                {{ $mensaje }}
                            </div>

                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="padding-top:30px;">
                            <a href="https://edomofusion.com.ar"
                                style="
                                background-color:#2563eb;
                                color:white;
                                padding:12px 24px;
                                text-decoration:none;
                                border-radius:6px;
                                display:inline-block;
                            ">
                                Ir al sitio
                            </a>
                        </td>
                    </tr>

                    <tr>
                        <td style="
                            padding-top:30px;
                            font-size:13px;
                            color:#6b7280;
                            text-align:center;
                        ">
                            <p style="margin:0;">
                                © {{ date('Y') }} EDOMO Fusion - Todos los derechos reservados.
                            </p>
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>

</html>