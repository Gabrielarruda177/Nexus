<!DOCTYPE html>
<html lang="pt-BR" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Nexus FATEC') — FATEC Itaquera</title>
    <meta name="description" content="Plataforma unificada de comunicação estudantil da FATEC Itaquera.">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS (via CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'nexus-teal': '#0097b2',
                        'nexus-deepteal': '#19707F',
                        'nexus-darkteal': '#1F464C',
                        'nexus-navy': '#0B1F3B',
                        'nexus-slate': '#1E2A3A',
                        'nexus-cloud': '#F4F7FB',
                        'nexus-border': '#DCE3EC',
                    },
                    fontFamily: {
                        sans: ['Inter', 'Montserrat', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <style>
        :root {
            --nexus-teal: #0097b2;
            --nexus-deepteal: #19707F;
            --nexus-darkteal: #1F464C;
            --nexus-navy: #0B1F3B;
            --nexus-cloud: #F4F7FB;
            --nexus-slate: #1E2A3A;
            --nexus-border: #DCE3EC;
        }
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background-color: var(--nexus-cloud);
            color: var(--nexus-slate);
        }
    </style>

    @stack('styles')
</head>
<body class="min-h-full flex flex-col bg-[#F4F7FB] text-[#1E2A3A] antialiased">
    @unless(View::hasSection('hideNav'))
    <!-- Top Header -->
    <header class="sticky top-0 z-40 bg-[#0B1F3B] text-white shadow-md border-b border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Brand / Logo -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#0097b2] to-[#19707F] flex items-center justify-center shadow-inner group-hover:scale-105 transition-transform">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-xl font-bold tracking-tight text-white leading-none">
                                NEXUS <span class="text-[#0097b2]">FATEC</span>
                            </span>
                            <span class="text-[11px] font-medium text-slate-300 tracking-wider uppercase mt-1">
                                FATEC Itaquera
                            </span>
                        </div>
                    </a>

                    <!-- Navigation Links for Logged Users -->
                    @auth
                        <nav class="hidden md:flex items-center gap-1 ml-8 pl-8 border-l border-slate-700/60 text-sm font-medium">
                            <a href="{{ route('comunicados.index') }}" class="px-3 py-1.5 rounded-lg {{ request()->routeIs('comunicados.*') ? 'bg-white/10 text-white' : 'text-slate-300 hover:text-white hover:bg-white/5' }} transition-colors">
                                Feed de Posts
                            </a>

                            <a href="{{ route('eventos.index') }}" class="px-3 py-1.5 rounded-lg {{ request()->routeIs('eventos.*') ? 'bg-white/10 text-white' : 'text-slate-300 hover:text-white hover:bg-white/5' }} transition-colors flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-[#0097b2]"></span>
                                Eventos & Agenda
                            </a>

                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="px-3 py-1.5 rounded-lg {{ request()->routeIs('admin.*') ? 'bg-white/10 text-white' : 'text-slate-300 hover:text-white hover:bg-white/5' }} transition-colors flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                                    Painel Admin
                                </a>
                            @elseif(auth()->user()->isProfessor())
                                <a href="{{ route('professor.dashboard') }}" class="px-3 py-1.5 rounded-lg {{ request()->routeIs('professor.*') ? 'bg-white/10 text-white' : 'text-slate-300 hover:text-white hover:bg-white/5' }} transition-colors flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-[#0097b2]"></span>
                                    Painel Professor
                                </a>
                            @endif
                        </nav>
                    @endauth
                </div>

                <!-- Right Side Actions -->
                <div class="flex items-center gap-3">
                    @auth
                        <!-- Active Persona Badge -->
                        @php
                            $roleData = match(auth()->user()->role) {
                                'administrador' => ['bg' => 'bg-amber-400/20 text-amber-300 border-amber-400/30', 'label' => 'Administrador'],
                                'professor' => ['bg' => 'bg-[#0097b2]/20 text-[#0097b2] border-[#0097b2]/30', 'label' => 'Professor'],
                                default => ['bg' => 'bg-cyan-400/20 text-cyan-300 border-cyan-400/30', 'label' => 'Aluno'],
                            };
                        @endphp
                        <span class="hidden sm:inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold border {{ $roleData['bg'] }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                            {{ $roleData['label'] }}
                        </span>

                        <!-- User Info & Logout Form -->
                        <div class="flex items-center gap-3 pl-2 sm:border-l sm:border-slate-700/60">
                            <div class="text-right hidden sm:block">
                                <p class="text-xs font-semibold text-white leading-tight truncate max-w-[160px]">
                                    {{ auth()->user()->name }}
                                </p>
                                <p class="text-[10px] text-slate-400 truncate max-w-[160px]">
                                    {{ auth()->user()->turmaMatriculada?->codigo ?? auth()->user()->turma ?? auth()->user()->email }}
                                </p>
                            </div>

                            <form action="{{ route('logout') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" title="Sair da conta" class="p-2 rounded-lg text-slate-300 hover:text-red-400 hover:bg-red-500/10 transition-colors flex items-center gap-1 text-xs font-medium">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    <span class="hidden sm:inline">Sair</span>
                                </button>
                            </form>
                        </div>
                    @else
                        <!-- Public / Guest State -->
                        <div class="flex items-center gap-2">
                            <a href="{{ route('home') }}" class="px-3.5 py-1.5 text-xs font-semibold text-white bg-[#0097b2] hover:bg-[#19707F] rounded-xl transition-colors shadow-sm">
                                Selecionar Perfil
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </header>
    @endunless

    <!-- Global Toast / Alerts -->
    @unless(View::hasSection('hideNav'))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full mt-4">
        @if(session('success'))
            <div class="flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl shadow-sm" role="alert">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="text-sm font-medium">{{ session('success') }}</div>
            </div>
        @endif

        @if(session('error'))
            <div class="flex items-center gap-3 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl shadow-sm" role="alert">
                <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="text-sm font-medium">{{ session('error') }}</div>
            </div>
        @endif

        @if(session('info'))
            <div class="flex items-center gap-3 p-4 bg-cyan-50 border border-cyan-200 text-cyan-800 rounded-2xl shadow-sm" role="alert">
                <svg class="w-5 h-5 text-[#0097b2] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="text-sm font-medium">{{ session('info') }}</div>
            </div>
        @endif
    </div>
    @endunless

    <!-- Main Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    @unless(View::hasSection('hideNav'))
    <!-- Footer -->
    <footer class="bg-white border-t border-[#DCE3EC] mt-16 text-slate-500 text-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-2">
                    <span class="font-bold text-[#0B1F3B] tracking-tight">NEXUS FATEC</span>
                    <span>—</span>
                    <span>Hackathon FATEC Itaquera 2026</span>
                </div>
                <div class="text-center sm:text-right text-slate-400">
                    <p class="italic">"O problema não é a falta de informação, mas a informação estar espalhada."</p>
                </div>
            </div>
        </div>
    </footer>
    @endunless

    @stack('scripts')
</body>
</html>
