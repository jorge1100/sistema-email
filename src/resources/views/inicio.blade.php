@extends('layouts.app')
@section('title','Inicio - Mi App Laravel')
@push('styles')
<style>
.hero{max-width:640px;margin:32px auto;padding:0 16px}
.card{background:#fff;border-radius:12px;padding:32px;box-shadow:0 1px 3px rgba(0,0,0,.08),0 4px 12px rgba(0,0,0,.05);text-align:center}
.card h1{font-size:26px;color:#1e293b;margin-bottom:8px;display:flex;align-items:center;justify-content:center;gap:10px}
.card p{color:#64748b;font-size:14px;line-height:1.6;margin-bottom:20px}
.btn-primary{background:#2563eb;color:#fff;border:none;padding:11px 22px;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;gap:8px}
.btn-primary:hover{background:#1d4ed8}
.features{display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-top:20px;text-align:left}
.feat{background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:16px}
.feat h3{font-size:14px;margin-bottom:4px}
.feat p{font-size:13px;color:#64748b;margin:0}
@media(max-width:600px){.features{grid-template-columns:1fr}}
</style>
@endpush
@section('content')
<div class="hero">
    <div class="card">
        <h1>👋 Bienvenido a Mi App Laravel</h1>
        <p>Sistema de envío de correos utilizando <strong>Brevo SMTP</strong> y <strong>Mailpit</strong> para pruebas en local. Gestioná tus envíos de forma rápida y segura.</p>
        <a href="{{ url('/contacto') }}" class="btn-primary">✉️ Enviar correo</a>
        <div class="features">
            <div class="feat"><h3>⚡ Envío rápido</h3><p>Correos vía SMTP con Laravel Mail.</p></div>
            <div class="feat"><h3>🧪 Mailpit</h3><p>Visualizá los mails sin salir de local.</p></div>
            <div class="feat"><h3>✅ Validación</h3><p>Campos validados y mensajes claros.</p></div>
            <div class="feat"><h3>🎨 Diseño moderno</h3><p>Interfaz limpia inspirada en Brevo.</p></div>
        </div>
    </div>
</div>
@endsection
