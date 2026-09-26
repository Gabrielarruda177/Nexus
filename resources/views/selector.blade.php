@extends('layouts.app')

@section('title', 'Seja bem-vindo ao Nexus FATEC — FATEC Itaquera')
@section('hideNav', true)

@push('styles')
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    :root {
        --primary-teal: #0097b2;
        --deep-teal: #19707F;
        --dark-teal: #1F464C;
        --night-teal: #1F3033;
        --dark-bg: #0A1929;
        --text-white: #FFFFFF;
        --text-gray: #E5E7EB;
        --shadow-sm: 0 4px 12px rgba(0, 0, 0, 0.15);
        --shadow-md: 0 8px 24px rgba(0, 0, 0, 0.25);
        --shadow-lg: 0 16px 40px rgba(0, 0, 0, 0.35);
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-image: url('{{ asset('img/fatec-bg.jpg') }}');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        min-height: 100vh;
        overflow-x: hidden;
        position: relative;
    }

    /* Overlay escuro com radial pulse para garantir legibilidade perfeita da FATEC */
    body::before {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: 
            linear-gradient(to right, rgba(10, 25, 41, 0.94) 0%, rgba(10, 25, 41, 0.88) 45%, rgba(10, 25, 41, 0.58) 100%),
            radial-gradient(circle at 20% 30%, rgba(0, 151, 178, 0.28) 0%, transparent 50%),
            radial-gradient(circle at 80% 70%, rgba(25, 112, 127, 0.22) 0%, transparent 50%);
        pointer-events: none;
        z-index: 0;
        animation: backgroundPulse 15s ease-in-out infinite;
    }

    @keyframes backgroundPulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.82; }
    }

    .main-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 60px 80px;
        gap: 80px;
        position: relative;
        z-index: 1;
        max-width: 1400px;
        margin: 0 auto;
    }

    .box-content {
        flex: 1;
        max-width: 620px;
        animation: slideInLeft 0.8s ease-out;
    }

    @keyframes slideInLeft {
        from {
            opacity: 0;
            transform: translateX(-50px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .brand-top-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 14px;
        border-radius: 9999px;
        background: rgba(0, 151, 178, 0.2);
        border: 1px solid rgba(0, 151, 178, 0.4);
        color: #00e0ff;
        font-size: 0.85rem;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
        margin-bottom: 20px;
        backdrop-filter: blur(8px);
    }

    .box-content h1 {
        font-size: 3.5rem;
        font-weight: 800;
        color: var(--text-white);
        line-height: 1.15;
        margin-bottom: 24px;
        text-shadow: 0 4px 16px rgba(0, 0, 0, 0.5);
        animation: fadeInUp 0.8s ease-out 0.2s both;
        letter-spacing: -0.02em;
    }

    .box-content h1 span.highlight {
        color: #00d2f0;
        text-shadow: 0 0 30px rgba(0, 210, 240, 0.4);
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .subtitle {
        font-size: 1.05rem;
        font-weight: 700;
        color: var(--text-gray);
        letter-spacing: 1.8px;
        text-transform: uppercase;
        margin-bottom: 32px;
        padding-bottom: 16px;
        border-bottom: 3px solid rgba(0, 151, 178, 0.5);
        animation: fadeInUp 0.8s ease-out 0.4s both;
    }

    .area-selection {
        display: flex;
        flex-direction: column;
        gap: 18px;
        animation: fadeInUp 0.8s ease-out 0.6s both;
    }

    .area-button {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        padding: 22px 30px;
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text-white);
        text-decoration: none;
        border-radius: 18px;
        background: rgba(255, 255, 255, 0.06);
        backdrop-filter: blur(12px);
        border: 2px solid rgba(255, 255, 255, 0.12);
        box-shadow: var(--shadow-sm);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .area-button .btn-icon-box {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 18px;
        background: rgba(255, 255, 255, 0.12);
        font-size: 1.3rem;
        transition: transform 0.3s ease;
    }

    .area-button:hover .btn-icon-box {
        transform: scale(1.1);
    }

    .area-button .btn-meta {
        display: flex;
        flex-direction: column;
    }

    .area-button .btn-label {
        font-size: 1.25rem;
        font-weight: 700;
        color: #FFFFFF;
        line-height: 1.2;
    }

    .area-button .btn-desc {
        font-size: 0.82rem;
        color: rgba(255, 255, 255, 0.7);
        font-weight: 500;
        margin-top: 3px;
    }

    .area-button::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.18), transparent);
        transition: left 0.6s;
    }

    .area-button:hover::before {
        left: 100%;
    }

    .area-button::after {
        content: '→';
        position: absolute;
        right: 30px;
        font-size: 1.6rem;
        opacity: 0;
        transform: translateX(-10px);
        transition: all 0.3s ease;
        color: #FFFFFF;
        font-weight: 800;
    }

    .area-button:hover::after {
        opacity: 1;
        transform: translateX(0);
    }

    .area-button:hover {
        transform: translateX(8px) scale(1.02);
        box-shadow: var(--shadow-lg);
        border-color: rgba(255, 255, 255, 0.35);
    }

    .area-button:active {
        transform: translateX(4px) scale(0.98);
    }

    /* Cores das Personas conforme a paleta oficial */
    .btn-administrador {
        background: linear-gradient(135deg, rgba(0, 151, 178, 0.32) 0%, rgba(11, 31, 59, 0.45) 100%);
        border-color: rgba(0, 151, 178, 0.45);
    }

    .btn-administrador:hover {
        background: linear-gradient(135deg, rgba(0, 151, 178, 0.55) 0%, rgba(11, 31, 59, 0.7) 100%);
        border-color: #0097b2;
        box-shadow: 0 10px 32px rgba(0, 151, 178, 0.45);
    }

    .btn-professor {
        background: linear-gradient(135deg, rgba(25, 112, 127, 0.32) 0%, rgba(31, 70, 76, 0.45) 100%);
        border-color: rgba(25, 112, 127, 0.45);
    }

    .btn-professor:hover {
        background: linear-gradient(135deg, rgba(25, 112, 127, 0.55) 0%, rgba(31, 70, 76, 0.7) 100%);
        border-color: #19707F;
        box-shadow: 0 10px 32px rgba(25, 112, 127, 0.45);
    }

    .btn-aluno {
        background: linear-gradient(135deg, rgba(0, 151, 178, 0.28) 0%, rgba(31, 48, 51, 0.45) 100%);
        border-color: rgba(0, 151, 178, 0.45);
    }

    .btn-aluno:hover {
        background: linear-gradient(135deg, rgba(0, 151, 178, 0.5) 0%, rgba(31, 48, 51, 0.7) 100%);
        border-color: #00d2f0;
        box-shadow: 0 10px 32px rgba(0, 210, 240, 0.4);
    }

    .area-button:nth-child(1) { animation: fadeInUp 0.8s ease-out 0.65s both; }
    .area-button:nth-child(2) { animation: fadeInUp 0.8s ease-out 0.80s both; }
    .area-button:nth-child(3) { animation: fadeInUp 0.8s ease-out 0.95s both; }

    /* Lado Direito: Logo FN Oficial Flutuante */
    .logo-container {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        animation: fadeInScale 1s ease-out 0.4s both;
    }

    @keyframes fadeInScale {
        from {
            opacity: 0;
            transform: scale(0.85);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    .logo-card {
        background: #FFFFFF;
        padding: 40px;
        border-radius: 40px;
        box-shadow: 0 24px 60px rgba(0, 0, 0, 0.5), 0 0 0 1px rgba(255, 255, 255, 0.2);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        animation: floatAnimation 6s ease-in-out infinite;
        max-width: 440px;
        width: 100%;
        text-align: center;
    }

    .logo-card img.logo-img {
        max-width: 260px;
        width: 100%;
        height: auto;
        object-fit: contain;
        display: block;
        margin: 0 auto;
    }

    .logo-caption {
        margin-top: 24px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 6px;
    }

    .logo-caption .title {
        font-size: 1.5rem;
        font-weight: 800;
        color: #0A1929;
        letter-spacing: -0.01em;
    }

    .logo-caption .title strong {
        color: #0097b2;
    }

    .logo-caption .sub {
        font-size: 0.85rem;
        font-weight: 600;
        color: #64748B;
        text-transform: uppercase;
        letter-spacing: 1.5px;
    }

    @keyframes floatAnimation {
        0%, 100% {
            transform: translateY(0px);
        }
        50% {
            transform: translateY(-16px);
        }
    }

    /* Demonstração / pitch rápida no rodapé da página */
    .pitch-tag {
        margin-top: 28px;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.85rem;
        color: rgba(255, 255, 255, 0.75);
        font-style: italic;
    }

    @media (max-width: 1200px) {
        .main-container {
            padding: 50px 40px;
            gap: 50px;
        }
        
        .box-content h1 {
            font-size: 3rem;
        }

        .logo-card {
            max-width: 360px;
            padding: 30px;
        }

        .logo-card img.logo-img {
            max-width: 220px;
        }
    }

    @media (max-width: 968px) {
        .main-container {
            flex-direction: column;
            padding: 50px 24px;
            gap: 40px;
            text-align: center;
        }

        .box-content {
            max-width: 100%;
        }

        .box-content h1 {
            font-size: 2.5rem;
        }

        .area-button {
            justify-content: center;
        }

        .area-button::after {
            display: none;
        }

        .logo-card {
            max-width: 300px;
            padding: 24px;
        }

        .logo-card img.logo-img {
            max-width: 180px;
        }
    }

    @media (max-width: 576px) {
        .main-container {
            padding: 30px 16px;
        }

        .box-content h1 {
            font-size: 2rem;
        }

        .area-button {
            padding: 16px 20px;
            font-size: 1.1rem;
        }
    }
</style>
@endpush

@section('content')
<div class="main-container">
    <!-- Lado Esquerdo: Chamada e Seleção de Áreas -->
    <div class="box-content">
        <div class="brand-top-pill">
            <span>🏛️</span> FATEC Itaquera 2026
        </div>

        <h1>
            Seja bem-vindo<br>ao <span class="highlight">Nexus FATEC</span>
        </h1>

        <div class="subtitle">
            Selecione a sua área de atuação
        </div>

        <div class="area-selection">
            <a href="{{ route('login.form', 'administrador') }}" class="area-button btn-administrador">
                <div class="btn-icon-box">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <div class="btn-meta">
                    <span class="btn-label">Administrador</span>
                    <span class="btn-desc">Gestão de turmas, docentes e moderação geral</span>
                </div>
            </a>

            <a href="{{ route('login.form', 'professor') }}" class="area-button btn-professor">
                <div class="btn-icon-box">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                    </svg>
                </div>
                <div class="btn-meta">
                    <span class="btn-label">Professor</span>
                    <span class="btn-desc">Publicação segmentada por turma e agenda de eventos</span>
                </div>
            </a>

            <a href="{{ route('login.form', 'aluno') }}" class="area-button btn-aluno">
                <div class="btn-icon-box">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <div class="btn-meta">
                    <span class="btn-label">Aluno</span>
                    <span class="btn-desc">Feed dinâmico estilo rede social, alertas urgentes e calendário</span>
                </div>
            </a>
        </div>

        <div class="pitch-tag">
            <span>💡</span> "O problema não é a falta de informação, mas a informação estar espalhada."
        </div>
    </div>

    <!-- Lado Direito: Logo Oficial FN Flutuante -->
    <div class="logo-container">
        <div class="logo-card">
            <img src="{{ asset('img/logo-fn.png') }}" alt="Logo Nexus FATEC" class="logo-img">
            <div class="logo-caption">
                <span class="title">NEXUS <strong>FATEC</strong></span>
                <span class="sub">Central Unificada Estudantil</span>
            </div>
        </div>
    </div>
</div>
@endsection
