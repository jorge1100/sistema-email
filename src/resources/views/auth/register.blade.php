@extends('layouts.app')
@section('title','Registro de Usuario - Mi App Laravel')
@push('styles')
<style>
.wrap{max-width:640px;margin:28px auto;padding:0 16px}
.card{background:#fff;border-radius:12px;box-shadow:0 1px 3px rgba(0,0,0,.08),0 8px 24px rgba(0,0,0,.06);padding:26px 28px 22px}
.card-header{display:flex;align-items:center;gap:16px;margin-bottom:22px}
.icon-circle{width:52px;height:52px;border-radius:50%;background:#e0ecff;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.card-header h1{font-size:19px;color:#1e293b;font-weight:700;margin:0}
.card-header p{font-size:13px;color:#64748b;margin:3px 0 0}
.form-group{margin-bottom:16px}
.label-row{display:flex;align-items:center;gap:6px;margin-bottom:6px}
.label-row label{font-size:13px;font-weight:600;color:#1e293b}
.label-row label span{color:#ef4444}
.label-row svg{width:16px;height:16px;fill:#475569}
.input{width:100%;border:1.5px solid #e2e8f0;border-radius:8px;padding:9px 12px;font-size:13px;color:#1e293b;outline:none;transition:.15s;background:#fff}
.input:focus{border-color:#2563eb;box-shadow:0 0 0 3px rgba(37,99,235,.12)}
.input.error{border-color:#ef4444}
.helper{font-size:11.5px;color:#94a3b8;margin-top:4px}
.error-msg{font-size:11.5px;color:#ef4444;margin-top:4px}
.success{background:#dcfce7;color:#166534;border:1px solid #86efac;padding:11px 14px;border-radius:8px;font-size:13px;margin-bottom:16px}
.errors{background:#fee2e2;color:#991b1b;border:1px solid #fca5a5;padding:11px 14px;border-radius:8px;font-size:13px;margin-bottom:16px}
.errors ul{margin:6px 0 0 18px}
.actions{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-top:18px;padding-top:16px;border-top:1px solid #f1f5f9}
.btn{border:none;border-radius:8px;padding:10px 18px;font-size:13px;font-weight:600;cursor:pointer;display:inline-flex;align-items:center;gap:7px;transition:.15s;text-decoration:none}
.btn-secondary{background:#f8fafc;color:#334155;border:1.5px solid #e2e8f0}
.btn-secondary:hover{background:#f1f5f9}
.btn-primary{background:#2563eb;color:#fff;box-shadow:0 1px 2px rgba(37,99,235,.25)}
.btn-primary:hover{background:#1d4ed8}
.btn svg{width:15px;height:15px}
.input::placeholder{color:#94a3b8}
.two-cols{display:grid;grid-template-columns:1fr 1fr;gap:14px}
@media(max-width:560px){.two-cols{grid-template-columns:1fr}}
</style>
@endpush
@section('content')
<div class="wrap">
    <div class="card">
        <div class="card-header">
            <div class="icon-circle">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="#2563eb"><path d="M15 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm-9-2V7H4v3H1v2h3v3h2v-3h3v-2H6zm9 4c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
            </div>
            <div>
                <h1>Formulario de Registro</h1>
                <p>Creá tu cuenta y recibí un correo de bienvenida vía Brevo SMTP.</p>
            </div>
        </div>

        @if(session('success'))
            <div class="success">✅ {{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="errors">
                <strong>Revisá los siguientes campos:</strong>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register.store') }}" novalidate>
            @csrf

            <div class="form-group">
                <div class="label-row">
                    <svg viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                    <label for="name">Nombre Completo <span>*</span></label>
                </div>
                <input class="input @error('name') error @enderror" type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Ej: Juan Pérez" required>
                @error('name')<div class="error-msg">{{ $message }}</div>@enderror
                <div class="helper">Entre 3 y 255 caracteres.</div>
            </div>

            <div class="form-group">
                <div class="label-row">
                    <svg viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4-8 5-8-5V6l8 5 8-5v2z"/></svg>
                    <label for="email">Correo Electrónico <span>*</span></label>
                </div>
                <input class="input @error('email') error @enderror" type="email" id="email" name="email" value="{{ old('email') }}" placeholder="ejemplo@correo.com" required>
                @error('email')<div class="error-msg">{{ $message }}</div>@enderror
                <div class="helper">Se valida formato RFC, existencia del dominio (DNS) y que no esté registrado.</div>
            </div>

            <div class="two-cols">
                <div class="form-group">
                    <div class="label-row">
                        <svg viewBox="0 0 24 24"><path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1s3.1 1.39 3.1 3.1v2z"/></svg>
                        <label for="password">Contraseña <span>*</span></label>
                    </div>
                    <input class="input @error('password') error @enderror" type="password" id="password" name="password" placeholder="Mínimo 8 caracteres" required>
                    @error('password')<div class="error-msg">{{ $message }}</div>@enderror
                    <div class="helper">Mínimo 8 caracteres.</div>
                </div>

                <div class="form-group">
                    <div class="label-row">
                        <svg viewBox="0 0 24 24"><path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1s3.1 1.39 3.1 3.1v2z"/></svg>
                        <label for="password_confirmation">Confirmar Contraseña <span>*</span></label>
                    </div>
                    <input class="input" type="password" id="password_confirmation" name="password_confirmation" placeholder="Repetí la contraseña" required>
                    <div class="helper">Debe coincidir con la contraseña.</div>
                </div>
            </div>

            <div class="actions">
                <button type="reset" class="btn btn-secondary" onclick="this.form.reset()">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/></svg>
                    Limpiar
                </button>
                <button type="submit" class="btn btn-primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg>
                    Registrarse
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
