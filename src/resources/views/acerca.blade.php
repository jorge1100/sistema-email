@extends('layouts.app')
@section('title','Acerca de - Mi App Laravel')
@push('styles')
<style>
.wrap{max-width:640px;margin:28px auto;padding:0 16px}
.card{background:#fff;border-radius:12px;padding:28px;box-shadow:0 1px 3px rgba(0,0,0,.08),0 4px 12px rgba(0,0,0,.05)}
.card h1{font-size:22px;color:#1e293b;margin-bottom:6px;display:flex;align-items:center;gap:10px}
.sub{color:#64748b;font-size:13px;margin-bottom:18px}
.block{background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:16px;margin-bottom:12px}
.block h3{font-size:14px;margin-bottom:6px;color:#1e293b}
.block p{font-size:13px;color:#64748b;line-height:1.6;margin:0}
.block ul{margin:6px 0 0 18px;font-size:13px;color:#475569;line-height:1.6}
.tag{display:inline-block;background:#dbeafe;color:#1d4ed8;padding:2px 8px;border-radius:20px;font-size:11px;font-weight:600}
</style>
@endpush
@section('content')
<div class="wrap">
    <div class="card">
        <h1>ℹ️ Acerca de</h1>
        <p class="sub">Información sobre este sistema de envío de correos.</p>

        <div class="block">
            <h3>¿Qué hace esta app? <span class="tag">Laravel 11</span></h3>
            <p>Permite enviar correos electrónicos a través de un formulario simple. En entorno local los mails se capturan con <strong>Mailpit</strong> (puerto 1025 SMTP / 8025 UI). En producción podés usar <strong>Brevo SMTP</strong> cambiando solo el <code>.env</code>.</p>
        </div>

        <div class="block">
            <h3>Stack</h3>
            <ul>
                <li>Laravel + Blade + PHP 8.5 - FPM</li>
                <li>Docker: Nginx, PHP, MariaDB, Mongo, Mailpit, Adminer</li>
                <li>Mailable <code>TestBrevoMail</code> con vista <code>emails.test-brevo</code></li>
            </ul>
        </div>

        <div class="block">
            <h3>Cómo probar</h3>
            <ul>
                <li>Ir a <strong>Enviar correo</strong> y completar el formulario.</li>
                <li>Abrir Mailpit en <code>http://localhost:7653</code> (usuario <code>jorge</code>).</li>
                <li>Ver el correo renderizado con datos de remitente y mensaje.</li>
            </ul>
        </div>

        <div class="block">
            <h3>EDOMO Fusion</h3>
            <p>© {{ date('Y') }} EDOMO Fusion - Todos los derechos reservados. <a href="https://edomofusion.com.ar" target="_blank" style="color:#2563eb;text-decoration:none">edomofusion.com.ar</a></p>
        </div>
    </div>
</div>
@endsection
