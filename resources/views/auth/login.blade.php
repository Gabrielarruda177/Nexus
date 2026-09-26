@extends('layouts.app')

@section('title', 'Login ' . $config['name'] . ' — Nexus FATEC')
@section('hideNav', true)

@push('styles')
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Montserrat', 'Inter', sans-serif;
        background-color: #FFFFFF;
        height: 100vh;
        min-height: 100vh;
        overflow-x: hidden;
    }

    .login-wrapper {
        display: flex;
        width: 100%;
        height: 100vh;
        min-height: 100vh;
    }

    /* Left Side with Large Persona Gradient Curve (per Image 2: border-radius: 0 100px 100px 0) */
    .login-left-side {
        width: 50%;
        background: linear-gradient(135deg, {{ $config['color_gradient_start'] }} 0%, {{ $config['color_gradient_end'] }} 100%);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        border-radius: 0 100px 100px 0;
        box-shadow: inset 0 0 50px rgba(255, 255, 255, 0.12), 8px 0 32px rgba(0, 0, 0, 0.15);
        padding: 40px;
        color: #FFFFFF;
        position: relative;
        animation: slideInLeft 0.8s cubic-bezier(0.4, 0, 0.2, 1) forwards;
    }

    .login-left-side .brand-box {
        text-align: center;
        max-width: 440px;
        animation: fadeIn 1.2s ease 0.3s both;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .logo-container-left {
        background: #FFFFFF;
        padding: 24px;
        border-radius: 32px;
        box-shadow: 0 16px 40px rgba(0, 0, 0, 0.25);
        margin-bottom: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 180px;
        height: 180px;
        transition: transform 0.3s ease;
    }

    .logo-container-left:hover {
        transform: scale(1.04);
    }

    .logo-container-left img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .brand-title {
        font-size: 2.4rem;
        font-weight: 800;
        letter-spacing: -0.02em;
        line-height: 1.15;
    }

    .brand-title span {
        color: #00d2f0;
    }

    .brand-subtitle {
        font-size: 0.95rem;
        color: rgba(255, 255, 255, 0.85);
        font-weight: 500;
        margin-top: 8px;
    }

    .persona-badge-box {
        margin-top: 32px;
        padding-top: 24px;
        border-top: 1px solid rgba(255, 255, 255, 0.2);
        max-width: 360px;
        text-align: center;
    }

    .badge-role {
        display: inline-block;
        padding: 6px 18px;
        border-radius: 9999px;
        background: rgba(255, 255, 255, 0.22);
        backdrop-filter: blur(8px);
        color: #FFFFFF;
        font-weight: 700;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        margin-bottom: 8px;
    }

    .badge-desc {
        font-size: 0.85rem;
        color: rgba(255, 255, 255, 0.8);
        line-height: 1.4;
    }

    /* Right Side with Centered Login Card (per Image 2) */
    .login-right-side {
        width: 50%;
        background-color: #FFFFFF;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 40px 24px;
        animation: slideInRight 0.8s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        position: relative;
    }

    .login-card-content {
        width: 100%;
        max-width: 400px;
        text-align: center;
        background-color: #FFFFFF;
        border-radius: 24px;
        padding: 40px 36px;
        box-shadow: 0 12px 36px rgba(0, 0, 0, 0.1);
        border: 1px solid #E2E8F0;
        transition: transform 0.3s ease;
    }

    .login-card-content:hover {
        transform: translateY(-3px);
    }

    .card-top-logo {
        width: 90px;
        height: 90px;
        margin: 0 auto 16px auto;
        border-radius: 20px;
        background: #FFFFFF;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
        padding: 12px;
        border: 1px solid #EDF2F7;
    }

    .card-top-logo img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .login-card-content h2 {
        color: {{ $config['color'] }};
        font-size: 24px;
        font-weight: 800;
        margin-bottom: 24px;
        letter-spacing: -0.02em;
    }

    .login-input-group {
        text-align: left;
        margin-bottom: 20px;
    }

    .login-input-group label {
        font-weight: 700;
        color: #1E2A3A;
        display: block;
        margin-bottom: 6px;
        font-size: 13px;
        letter-spacing: 0.3px;
    }

    .login-input-wrapper {
        position: relative;
        display: flex;
        align-items: center;
        border: 1.5px solid #DCE3EC;
        border-radius: 12px;
        padding: 11px 14px;
        transition: all 0.3s ease;
        background-color: #F8FAFC;
    }

    .login-input-wrapper:focus-within {
        border-color: {{ $config['color'] }};
        box-shadow: 0 0 0 3px {{ $config['color'] }}25;
        background-color: #FFFFFF;
    }

    .login-input-wrapper input {
        flex: 1;
        border: none;
        outline: none;
        font-size: 14px;
        color: #1E2A3A;
        background: transparent;
        font-weight: 500;
    }

    .login-icon-left {
        color: {{ $config['color'] }};
        margin-right: 12px;
        font-size: 18px;
        flex-shrink: 0;
    }

    .login-icon-right {
        color: #94A3B8;
        margin-left: 8px;
        cursor: pointer;
        transition: 0.3s;
    }

    .login-icon-right:hover {
        color: {{ $config['color'] }};
    }

    .btn-login-action {
        width: 100%;
        background: linear-gradient(135deg, {{ $config['color_gradient_start'] }} 0%, {{ $config['color_gradient_end'] }} 100%);
        color: #FFFFFF;
        border: none;
        border-radius: 50px;
        padding: 14px 0;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 6px 20px {{ $config['color'] }}40;
    }

    .btn-login-action:hover {
        filter: brightness(1.1);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px {{ $config['color'] }}60;
    }

    @keyframes slideInLeft {
        from { transform: translateX(-60px); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }

    @keyframes slideInRight {
        from { transform: translateX(60px); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(16px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @media (max-width: 950px) {
        body {
            height: auto;
            overflow-y: auto;
        }
        .login-wrapper {
            flex-direction: column;
            height: auto;
            min-height: 100vh;
        }
        .login-left-side {
            width: 100%;
            border-radius: 0 0 50px 50px;
            padding: 40px 20px;
            min-height: 260px;
        }
        .logo-container-left {
            width: 120px;
            height: 120px;
            padding: 16px;
            margin-bottom: 16px;
        }
        .brand-title {
            font-size: 1.8rem;
        }
        .login-right-side {
            width: 100%;
            padding: 30px 16px 50px 16px;
        }
        .login-card-content {
            max-width: 360px;
            margin-top: -20px;
        }
    }
</style>
@endpush

@section('content')
<div class="login-wrapper">
    <!-- Lado Esquerdo com Curvatura Elegante e Logo FN Oficial -->
    <div class="login-left-side">
        <div class="brand-box">
            <!-- Logo FN no Lado Esquerdo -->
            <div class="logo-container-left">
                <img src="{{ asset('img/logo-fn.png') }}" alt="Logo Nexus FATEC">
            </div>

            <h1 class="brand-title">
                NEXUS <span>FATEC</span>
            </h1>
            <p class="brand-subtitle">
                Central Integrada de Comunicação • FATEC Itaquera
            </p>

            <div class="persona-badge-box">
                <span class="badge-role">{{ $config['name'] }}</span>
                <p class="badge-desc">{{ $config['subtitle'] }}</p>
            </div>
        </div>
    </div>

    <!-- Lado Direito com Cartão de Login Elevado -->
    <div class="login-right-side">
        <div class="login-card-content">
            <!-- Logo FN no Topo do Formulário -->
            <div class="card-top-logo">
                <img src="{{ asset('img/logo-fn.png') }}" alt="Logo Nexus FATEC">
            </div>

            <h2>{{ $config['title_login'] ?? 'Login ' . $config['name'] }}</h2>

            <form action="{{ route('login.submit', ['persona' => $persona]) }}" method="POST" class="space-y-4">
                @csrf

                <!-- E-mail -->
                <div class="login-input-group">
                    <label for="email">E-mail Institucional</label>
                    <div class="login-input-wrapper">
                        <span class="login-icon-left">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                            </svg>
                        </span>
                        <input type="email"
                               name="email"
                               id="email"
                               value="{{ old('email', $config['demo_email']) }}"
                               required
                               autocomplete="email"
                               placeholder="nome@fatec.sp.gov.br">
                    </div>
                    @error('email')
                        <p class="text-xs text-rose-600 mt-1 font-semibold text-left">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Senha -->
                <div class="login-input-group">
                    <label for="password">Senha de Acesso</label>
                    <div class="login-input-wrapper">
                        <span class="login-icon-left">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </span>
                        <input type="password"
                               name="password"
                               id="password"
                               value="{{ $config['demo_password'] }}"
                               required
                               autocomplete="current-password"
                               placeholder="••••••••">
                        <span class="login-icon-right" onclick="togglePasswordVisibility()" title="Exibir/Ocultar senha">
                            <svg id="eye-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </span>
                    </div>
                    @error('password')
                        <p class="text-xs text-rose-600 mt-1 font-semibold text-left">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Lembrar de mim -->
                <div class="flex items-center justify-between text-xs text-slate-600 py-1">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded text-[#0097b2] focus:ring-0">
                        <span>Lembrar de mim</span>
                    </label>
                    <span class="text-slate-400">Ambiente Seguro</span>
                </div>

                <!-- Botão de Entrar -->
                <div class="pt-2">
                    <button type="submit" class="btn-login-action">
                        ENTRAR
                    </button>
                </div>
            </form>

            <!-- Preencher Conta Demo -->
            <div class="mt-6 pt-5 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="text-slate-500 font-medium">Demonstração:</span>
                <button type="button"
                        onclick="fillDemo('{{ $config['demo_email'] }}', '{{ $config['demo_password'] }}')"
                        class="font-semibold text-slate-700 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 px-3 py-1.5 rounded-lg transition-colors">
                    Preencher Conta Demo
                </button>
            </div>

            <!-- Voltar à tela inicial -->
            <div class="mt-4 pt-2">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#0097b2] hover:underline">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Voltar para seleção de perfil
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function fillDemo(email, pass) {
    document.getElementById('email').value = email;
    document.getElementById('password').value = pass;
}

function togglePasswordVisibility() {
    const input = document.getElementById('password');
    if (input.type === 'password') {
        input.type = 'text';
    } else {
        input.type = 'password';
    }
}
</script>
@endsection
