@extends('layouts.app')
@section('title','Enviar correo - Mi App Laravel')
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
textarea.input{resize:vertical;min-height:96px}
.helper{font-size:11.5px;color:#94a3b8;margin-top:4px}
.error-msg{font-size:11.5px;color:#ef4444;margin-top:4px}
.success{background:#dcfce7;color:#166534;border:1px solid #86efac;padding:11px 14px;border-radius:8px;font-size:13px;margin-bottom:16px}
.errors{background:#fee2e2;color:#991b1b;border:1px solid #fca5a5;padding:11px 14px;border-radius:8px;font-size:13px;margin-bottom:16px}
.errors ul{margin:6px 0 0 18px}
.actions{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-top:18px;padding-top:16px;border-top:1px solid #f1f5f9}
.btn{border:none;border-radius:8px;padding:10px 18px;font-size:13px;font-weight:600;cursor:pointer;display:inline-flex;align-items:center;gap:7px;transition:.15s}
.btn-secondary{background:#f8fafc;color:#334155;border:1.5px solid #e2e8f0}
.btn-secondary:hover{background:#f1f5f9}
.btn-primary{background:#2563eb;color:#fff;box-shadow:0 1px 2px rgba(37,99,235,.25)}
.btn-primary:hover{background:#1d4ed8}
.btn svg{width:15px;height:15px}
.input::placeholder{color:#94a3b8}
</style>
@endpush
@section('content')
<div class="wrap">
    <div class="card">
        <div class="card-header">
            <div class="icon-circle">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="#2563eb"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4-8 5-8-5V6l8 5 8-5v2z"/></svg>
            </div>
            <div>
                <h1>Envío de correo</h1>
                <p>Completá los datos y enviá un correo utilizando Brevo SMTP.</p>
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

        <form method="POST" action="{{ url('/contacto') }}" novalidate>
            @csrf

            <div class="form-group">
                <div class="label-row">
                    <svg viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4-8 5-8-5V6l8 5 8-5v2z"/></svg>
                    <label for="email">Correo destinatario <span>*</span></label>
                </div>
                <input class="input @error('email') error @enderror" type="email" id="email" name="email" value="{{ old('email') }}" placeholder="ejemplo@correo.com" required>
                @error('email')<div class="error-msg">{{ $message }}</div>@enderror
                <div class="helper">Ingresá la dirección de correo del destinatario.</div>
            </div>

            <div class="form-group">
                <div class="label-row">
                    <svg viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                    <label for="nombre">Nombre <span>*</span></label>
                </div>
                <input class="input @error('nombre') error @enderror" type="text" id="nombre" name="nombre" value="{{ old('nombre') }}" placeholder="Tu nombre" required>
                @error('nombre')<div class="error-msg">{{ $message }}</div>@enderror
                <div class="helper">Tu nombre aparecerá en el correo.</div>
            </div>

            <div class="form-group">
                <div class="label-row">
                    <svg viewBox="0 0 24 24"><path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg>
                    <label for="telefono">Teléfono (opcional)</label>
                </div>
                <input class="input @error('telefono') error @enderror" type="text" id="telefono" name="telefono" value="{{ old('telefono') }}" placeholder="Ej: 3704123456">
                @error('telefono')<div class="error-msg">{{ $message }}</div>@enderror
                <div class="helper">Podés ingresar un número de contacto (mínimo 8 caracteres).</div>
            </div>

            <div class="form-group">
                <div class="label-row">
                    <svg viewBox="0 0 24 24"><path d="M21.41 11.58l-9-9C12.05 2.22 11.55 2 11 2H4c-1.1 0-2 .9-2 2v7c0 .55.22 1.05.59 1.42l9 9c.39.39 1.02.39 1.41 0l7-7c.39-.39.39-1.02 0-1.41zM5.5 7c-.83 0-1.5-.67-1.5-1.5S4.67 4 5.5 4 7 4.67 7 5.5 6.33 7 5.5 7z"/></svg>
                    <label for="asunto">Asunto <span>*</span></label>
                </div>
                <input class="input @error('asunto') error @enderror" type="text" id="asunto" name="asunto" value="{{ old('asunto') }}" placeholder="Asunto del correo" required>
                @error('asunto')<div class="error-msg">{{ $message }}</div>@enderror
                <div class="helper">Ingresá un asunto breve y descriptivo.</div>
            </div>

            <div class="form-group">
                <div class="label-row">
                    <svg viewBox="0 0 24 24"><path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/></svg>
                    <label for="mensaje">Mensaje <span>*</span></label>
                </div>
                <textarea class="input @error('mensaje') error @enderror" id="mensaje" name="mensaje" rows="4" placeholder="Escribí aquí tu mensaje..." required maxlength="5000">{{ old('mensaje') }}</textarea>
                @error('mensaje')<div class="error-msg">{{ $message }}</div>@enderror
                <div class="helper">Podés escribir hasta 5000 caracteres.</div>
            </div>

            <div class="actions">
                <button type="reset" class="btn btn-secondary" onclick="this.form.reset()">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/></svg>
                    Limpiar
                </button>
                <button type="submit" class="btn btn-primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                    Enviar correo
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
