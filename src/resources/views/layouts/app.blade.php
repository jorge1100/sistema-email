<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mi App Laravel')</title>
    <style>
        *{margin:0;padding:0;box-sizing:border-box}
        body{font-family:'Segoe UI',system-ui,-apple-system,Arial,sans-serif;background:#eef2f7;min-height:100vh;color:#1f2937}
        .navbar{background:#304156;color:#cbd5e1;display:flex;align-items:center;justify-content:space-between;padding:0 20px;height:52px;box-shadow:0 2px 4px rgba(0,0,0,.15)}
        .navbar-left{display:flex;align-items:center;gap:32px}
        .navbar-brand{color:#fff;font-weight:700;font-size:15px;display:flex;align-items:center;gap:8px;white-space:nowrap;text-decoration:none}
        .navbar-brand svg{flex-shrink:0}
        .navbar-nav{display:flex;align-items:center;gap:6px;height:100%}
        .navbar-nav a{color:#cbd5e1;text-decoration:none;font-size:13px;padding:0 14px;height:100%;display:flex;align-items:center;gap:6px;border-bottom:3px solid transparent;transition:.15s}
        .navbar-nav a:hover{color:#fff}
        .navbar-nav a.active{color:#fff;border-bottom-color:#2563eb;background:rgba(255,255,255,.06)}
        .navbar-nav svg{width:16px;height:16px;opacity:.9}
        .container{max-width:640px;margin:28px auto;padding:0 16px}
        .mobile-toggle{display:none;background:none;border:none;color:#fff;cursor:pointer;padding:6px}
        @media(max-width:640px){
            .navbar-nav{display:none;position:absolute;top:52px;left:0;right:0;background:#304156;flex-direction:column;height:auto;gap:0;z-index:20}
            .navbar-nav.open{display:flex}
            .navbar-nav a{height:48px;width:100%;border-bottom:1px solid rgba(255,255,255,.08)}
            .navbar{position:relative}
            .mobile-toggle{display:block}
        }
    </style>
    @stack('styles')
</head>
<body>
    <nav class="navbar">
        <div class="navbar-left">
            <a href="{{ url('/') }}" class="navbar-brand">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                Mi App Laravel
            </a>
            <div class="navbar-nav" id="navMenu">
                <a href="{{ url('/') }}" class="{{ request()->is('/') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
                    Inicio
                </a>
                <a href="{{ url('/contacto') }}" class="{{ request()->is('contacto') || request()->is('enviar-correo') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4-8 5-8-5V6l8 5 8-5v2z"/></svg>
                    Enviar correo
                </a>
                <a href="{{ url('/register') }}" class="{{ request()->is('register') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M15 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm-9-2V7H4v3H1v2h3v3h2v-3h3v-2H6zm9 4c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                    Registro
                </a>
                <a href="{{ url('/acerca-de') }}" class="{{ request()->is('acerca-de') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                    Acerca de
                </a>
            </div>
        </div>
        <button class="mobile-toggle" onclick="document.getElementById('navMenu').classList.toggle('open')" aria-label="Menu">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
        </button>
    </nav>
    @yield('content')
</body>
</html>
