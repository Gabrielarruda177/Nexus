@extends('layouts.app')

@section('title', $comunicado->titulo . ' — Nexus FATEC')

@section('content')
<div class="py-8 px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto">
    <!-- Breadcrumb & Back -->
    <div class="flex items-center justify-between mb-6">
        <nav class="flex items-center gap-2 text-xs font-semibold text-slate-500">
            <a href="{{ route('comunicados.index') }}" class="hover:text-slate-800 transition-colors">Comunicados</a>
            <span>/</span>
            <span class="text-slate-400">{{ $comunicado->categoria }}</span>
            <span>/</span>
            <span class="text-slate-700 truncate max-w-[200px]">{{ $comunicado->titulo }}</span>
        </nav>

        <a href="{{ route('comunicados.index') }}"
           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-[#DCE3EC] bg-white text-xs font-bold text-slate-700 hover:bg-slate-50 transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Voltar ao feed
        </a>
    </div>

    <!-- Main Card -->
    <article class="bg-white rounded-3xl p-6 sm:p-10 border border-[#DCE3EC] shadow-sm {{ $comunicado->isUrgente() ? 'border-t-8 border-t-[#D64545]' : '' }}">
        <!-- Badges & Metadata -->
        <div class="flex flex-wrap items-center justify-between gap-3 mb-6 pb-6 border-b border-slate-100">
            <div class="flex flex-wrap items-center gap-2">
                @php
                    $badgeColor = match($comunicado->categoria) {
                        'Acadêmico' => '#1B4B9C',
                        'Eventos' => '#1C8C82',
                        'Avisos' => '#D98C2B',
                        'Turmas' => '#5B4FCF',
                        'Urgente' => '#D64545',
                        default => '#1B4B9C',
                    };
                @endphp

                <span class="px-3 py-1 rounded-full text-xs font-bold"
                      style="background-color: {{ $badgeColor }}15; color: {{ $badgeColor }};">
                    {{ $comunicado->categoria }}
                </span>

                @if($comunicado->isUrgente())
                    <span class="px-3 py-1 rounded-full text-xs font-extrabold bg-[#D64545] text-white uppercase tracking-wider flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-white animate-ping"></span>
                        COMUNICADO URGENTE
                    </span>
                @elseif($comunicado->isImportante())
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800">
                        Importante
                    </span>
                @endif

                @if($comunicado->turma)
                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-700">
                        Turma: {{ $comunicado->turma }}
                    </span>
                @endif
            </div>

            <div class="text-xs text-slate-500 font-medium">
                Publicado em {{ $comunicado->created_at->translatedFormat('d \d\e F \d\e Y \à\s H:i') }}
            </div>
        </div>

        <!-- Title -->
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight leading-tight mb-6">
            {{ $comunicado->titulo }}
        </h1>

        <!-- Author info block -->
        <div class="flex items-center gap-3 p-4 rounded-2xl bg-slate-50 border border-slate-100 mb-8">
            <div class="w-10 h-10 rounded-full bg-[#1B4B9C] text-white flex items-center justify-center font-bold text-sm">
                {{ strtoupper(substr($comunicado->autor->name ?? 'A', 0, 1)) }}
            </div>
            <div>
                <p class="text-xs font-bold text-slate-900">{{ $comunicado->autor->name ?? 'Coordenação FATEC' }}</p>
                <p class="text-[11px] text-slate-500">{{ $comunicado->autor->role_label ?? 'Equipe FATEC' }} • {{ $comunicado->autor->email }}</p>
            </div>
        </div>

        <!-- Body Content -->
        <div class="prose max-w-none text-slate-700 text-sm sm:text-base leading-relaxed whitespace-pre-line font-normal">
            {{ $comunicado->descricao }}
        </div>

        @if($comunicado->data_evento)
            <div class="mt-8 p-4 rounded-2xl bg-sky-50 border border-sky-200 flex items-center gap-3">
                <svg class="w-6 h-6 text-[#1B4B9C] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <div class="text-xs">
                    <strong class="font-bold text-[#1B4B9C]">Data associada a este comunicado:</strong>
                    <span class="text-slate-700">{{ $comunicado->data_evento->format('d/m/Y') }}</span>
                </div>
            </div>
        @endif
    </article>

    <!-- Related Announcements -->
    @if($relacionados->isNotEmpty())
        <div class="mt-12">
            <h2 class="text-lg font-bold text-[#0B1F3B] mb-4">
                Outros comunicados em <span class="text-[#1B4B9C]">{{ $comunicado->categoria }}</span>
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                @foreach($relacionados as $rel)
                    <a href="{{ route('comunicados.show', $rel) }}"
                       class="bg-white rounded-2xl p-4 border border-[#DCE3EC] hover:border-[#1B4B9C] shadow-sm hover:shadow transition-all block">
                        <span class="text-[10px] text-slate-400 font-medium block mb-1">
                            {{ $rel->created_at->format('d/m/Y') }}
                        </span>
                        <h3 class="text-xs font-bold text-slate-900 line-clamp-2 hover:text-[#1B4B9C]">
                            {{ $rel->titulo }}
                        </h3>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
