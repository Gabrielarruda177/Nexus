@extends('layouts.app')

@section('title', 'Busca de Comunicados — Nexus FATEC')

@section('content')
<div class="py-8 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
    <!-- Header & Search Box -->
    <div class="mb-8">
        <a href="{{ route('comunicados.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-slate-800 transition-colors mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Voltar para todos os comunicados
        </a>

        <div class="bg-white rounded-3xl p-6 border border-[#DCE3EC] shadow-sm">
            <h1 class="text-xl font-bold text-[#0B1F3B] mb-2">
                Busca de Comunicados e Avisos
            </h1>
            <p class="text-xs text-slate-500 mb-4">
                Pesquise por títulos, conteúdos, nomes de professores ou códigos de turma.
            </p>

            <form action="{{ route('comunicados.busca') }}" method="GET" class="flex gap-2">
                <div class="relative flex-grow">
                    <input type="text"
                           name="q"
                           value="{{ $q }}"
                           placeholder="Digite sua busca..."
                           class="w-full px-4 py-3 pl-11 rounded-2xl border border-[#DCE3EC] focus:outline-none focus:ring-2 focus:ring-[#1B4B9C] text-sm text-slate-800" />
                    <div class="absolute left-4 top-3.5 text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 1014 0z" />
                        </svg>
                    </div>
                </div>
                <button type="submit" class="px-6 py-3 rounded-2xl bg-[#1B4B9C] hover:bg-[#143976] text-white text-xs font-bold transition-colors">
                    Pesquisar
                </button>
            </form>
        </div>
    </div>

    <!-- Search Status -->
    <div class="flex items-center justify-between mb-6">
        @if(!empty($q))
            <p class="text-sm text-slate-600">
                Exibindo resultados para: <strong class="text-slate-900 font-semibold">"{{ $q }}"</strong>
                <span class="text-slate-400">({{ $comunicados->total() }} encontrados)</span>
            </p>
        @else
            <p class="text-sm text-slate-600">
                Digite um termo para pesquisar ou explore todos os comunicados abaixo.
            </p>
        @endif
    </div>

    <!-- Results Cards -->
    @if($comunicados->isEmpty())
        <div class="bg-white rounded-3xl p-12 text-center border border-[#DCE3EC]">
            <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 1014 0z" />
                </svg>
            </div>
            <h3 class="text-base font-bold text-slate-800">Nenhum resultado encontrado para "{{ $q }}"</h3>
            <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">
                Tente verificar a ortografia ou buscar por termos mais genéricos, como "prova", "inscrição" ou "DSM".
            </p>
            <a href="{{ route('comunicados.index') }}" class="inline-block mt-4 text-xs font-bold text-[#1B4B9C] hover:underline">
                Ver todos os comunicados
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($comunicados as $comunicado)
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
                <a href="{{ route('comunicados.show', $comunicado) }}"
                   class="group bg-white rounded-3xl p-6 border border-[#DCE3EC] hover:border-[#1B4B9C] shadow-sm hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200 flex flex-col justify-between {{ $comunicado->isUrgente() ? 'border-t-4 border-t-[#D64545]' : '' }}">
                    <div>
                        <div class="flex items-center justify-between gap-2 mb-3">
                            <span class="px-2.5 py-1 rounded-full text-[11px] font-bold tracking-wide"
                                  style="background-color: {{ $badgeColor }}15; color: {{ $badgeColor }};">
                                {{ $comunicado->categoria }}
                            </span>

                            @if($comunicado->isUrgente())
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-[#D64545] text-white uppercase">
                                    URGENTE
                                </span>
                            @endif
                        </div>

                        <h3 class="font-bold text-slate-900 group-hover:text-[#1B4B9C] transition-colors text-base line-clamp-2">
                            {{ $comunicado->titulo }}
                        </h3>

                        <p class="text-slate-600 text-xs mt-2 line-clamp-3 leading-relaxed">
                            {{ $comunicado->descricao }}
                        </p>
                    </div>

                    <div class="mt-5 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                        <span class="truncate max-w-[130px] font-medium text-slate-700">
                            {{ $comunicado->autor->name ?? 'FATEC' }}
                        </span>
                        <span class="text-[11px]">
                            {{ $comunicado->created_at->format('d/m/Y') }}
                        </span>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $comunicados->links() }}
        </div>
    @endif
</div>
@endsection
