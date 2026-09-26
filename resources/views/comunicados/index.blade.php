@extends('layouts.app')

@section('title', 'Feed de Comunicados — Nexus FATEC')

@section('content')
<div class="py-8 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
    <!-- Top Search & Banner (Social Feed Header) -->
    <div class="bg-gradient-to-r from-[#0B1F3B] via-[#19707F] to-[#0097b2] rounded-3xl p-6 sm:p-8 text-white shadow-lg mb-8 relative overflow-hidden">
        <div class="max-w-3xl relative z-10">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-white/15 backdrop-blur-sm text-white mb-3">
                <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                Feed Oficial — FATEC Itaquera
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">
                Mural de Comunicados & Avisos
            </h1>
            <p class="text-teal-100 text-xs sm:text-sm mt-1">
                Avisos direcionados à sua turma, novidades do campus e prazos acadêmicos centralizados.
            </p>

            <!-- Search Form -->
            <form action="{{ route('comunicados.busca') }}" method="GET" class="mt-5">
                <div class="flex items-center gap-2 bg-white rounded-2xl p-1.5 shadow-md">
                    <div class="pl-3 text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 1014 0z" />
                        </svg>
                    </div>
                    <input type="text"
                           name="q"
                           value="{{ $searchQuery ?? request('q') }}"
                           placeholder="Pesquisar por assunto, professor, turma ou palavra-chave..."
                           class="w-full px-3 py-2 text-sm text-slate-800 placeholder-slate-400 bg-transparent focus:outline-none font-medium" />
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#0097b2] hover:bg-[#19707F] text-white text-xs font-bold transition-colors shrink-0">
                        Pesquisar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Urgent Section (Red Alert exclusively per Section 6) -->
    @if($urgentes->isNotEmpty() && !request('categoria') && !request('q') && !request('turma_id'))
        <div class="mb-8">
            <div class="flex items-center gap-2.5 mb-3">
                <span class="relative flex h-3.5 w-3.5">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#D64545] opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3.5 w-3.5 bg-[#D64545]"></span>
                </span>
                <h2 class="text-sm font-extrabold text-[#D64545] uppercase tracking-wider">
                    Comunicados Urgentes & Avisos Imediatos
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($urgentes as $urgente)
                    @php
                        $urgentePayload = [
                            'id' => $urgente->id,
                            'titulo' => $urgente->titulo,
                            'descricao' => $urgente->descricao,
                            'categoria' => $urgente->categoria,
                            'categoria_color' => '#D64545',
                            'is_urgente' => true,
                            'autor_name' => $urgente->autor->name ?? 'Coordenação FATEC',
                            'autor_role' => $urgente->autor->role_label ?? 'Docente',
                            'autor_initial' => strtoupper(substr($urgente->autor->name ?? 'C', 0, 1)),
                            'date_formatted' => 'Publicado ' . $urgente->created_at->diffForHumans() . ' • ' . $urgente->created_at->format('d/m/Y H:i'),
                            'turma_label' => $urgente->turmaRelacionada ? ('🎯 Turma: ' . $urgente->turmaRelacionada->label_completo) : ($urgente->turma ? ('🎯 Turma: ' . $urgente->turma) : '📢 Geral (Todas as Turmas)'),
                            'data_evento' => $urgente->data_evento ? $urgente->data_evento->format('d/m/Y') : null,
                        ];
                    @endphp
                    <div onclick="openPostModal({{ json_encode($urgentePayload) }})"
                         class="cursor-pointer group bg-rose-50/80 border-2 border-[#D64545] rounded-2xl p-5 hover:bg-rose-100/70 transition-all shadow-sm hover:shadow-md flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between gap-2 mb-2">
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-extrabold bg-[#D64545] text-white tracking-wide uppercase">
                                    URGENTE
                                </span>
                                <span class="text-[11px] font-semibold text-rose-700">
                                    {{ $urgente->created_at->translatedFormat('d/m H:i') }}
                                </span>
                            </div>
                            <h3 class="font-bold text-slate-900 group-hover:text-[#D64545] transition-colors line-clamp-2 text-sm">
                                {{ $urgente->titulo }}
                            </h3>
                            <p class="text-slate-600 text-xs mt-1.5 line-clamp-2 leading-relaxed">
                                {{ $urgente->descricao }}
                            </p>
                        </div>

                        <div class="mt-3 pt-3 border-t border-rose-200 flex items-center justify-between text-xs text-rose-900 font-semibold">
                            <span class="truncate max-w-[170px]">
                                {{ $urgente->autor->name ?? 'Coordenação' }}
                            </span>
                            <span class="group-hover:translate-x-1 transition-transform flex items-center gap-1 font-bold">
                                Abrir aviso ↗
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Filters Row: Categories & Turmas Filter -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 pb-4 border-b border-slate-200">
        <!-- Category Filter Pills -->
        <div class="flex items-center gap-2 overflow-x-auto no-scrollbar pb-1">
            <a href="{{ route('comunicados.index', array_filter(['turma_id' => request('turma_id')])) }}"
               class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all shrink-0 {{ !request('categoria') ? 'bg-[#0B1F3B] text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-[#DCE3EC]' }}">
                Todos os Posts
            </a>
            @foreach($categorias as $cat => $hex)
                <a href="{{ route('comunicados.index', array_filter(['categoria' => $cat, 'turma_id' => request('turma_id')])) }}"
                   class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all shrink-0 flex items-center gap-1.5 {{ request('categoria') === $cat ? 'text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-[#DCE3EC]' }}"
                   style="{{ request('categoria') === $cat ? 'background-color: ' . $hex . ';' : '' }}">
                    <span class="w-2 h-2 rounded-full" style="background-color: {{ $hex }};"></span>
                    {{ $cat }}
                </a>
            @endforeach
        </div>

        <!-- Filter by Turma -->
        <div class="flex items-center gap-2 shrink-0">
            <label for="filtro-turma" class="text-xs font-bold text-slate-600">Turma:</label>
            <select id="filtro-turma"
                    onchange="filterTurma(this.value)"
                    class="px-3 py-1.5 rounded-xl border border-[#DCE3EC] bg-white text-xs font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#0097b2]">
                <option value="">Todas as Turmas (Geral)</option>
                @foreach($turmas as $t)
                    <option value="{{ $t->id }}" {{ request('turma_id') == $t->id ? 'selected' : '' }}>
                        {{ $t->codigo }} - {{ $t->semestre }} ({{ $t->periodo }})
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Social Network Feed Layout (Left: Posts stream / Right: Calendar & All Events) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Posts Stream (Social Cards) -->
        <div class="lg:col-span-2 space-y-6">
            @if($comunicados->isEmpty())
                <div class="bg-white rounded-3xl p-12 text-center border border-[#DCE3EC]">
                    <div class="w-14 h-14 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-800">Nenhum post encontrado</h3>
                    <p class="text-xs text-slate-500 mt-1">Nenhum comunicado publicado para os filtros selecionados.</p>
                    <a href="{{ route('comunicados.index') }}" class="inline-block mt-3 text-xs font-bold text-[#0097b2] hover:underline">
                        Limpar filtros e ver todos os posts
                    </a>
                </div>
            @else
                @foreach($comunicados as $post)
                    @php
                        $badgeColor = match($post->categoria) {
                            'Acadêmico' => '#0097b2',
                            'Eventos' => '#1C8C82',
                            'Avisos' => '#D98C2B',
                            'Turmas' => '#19707F',
                            'Urgente' => '#D64545',
                            default => '#0097b2',
                        };

                        $postPayload = [
                            'id' => $post->id,
                            'titulo' => $post->titulo,
                            'descricao' => $post->descricao,
                            'categoria' => $post->categoria,
                            'categoria_color' => $badgeColor,
                            'is_urgente' => $post->isUrgente(),
                            'autor_name' => $post->autor->name ?? 'Coordenação FATEC',
                            'autor_role' => $post->autor->role_label ?? 'Docente',
                            'autor_initial' => strtoupper(substr($post->autor->name ?? 'F', 0, 1)),
                            'date_formatted' => 'Publicado ' . $post->created_at->diffForHumans() . ' • ' . $post->created_at->format('d/m/Y H:i'),
                            'turma_label' => $post->turmaRelacionada ? ('🎯 Turma: ' . $post->turmaRelacionada->label_completo) : ($post->turma ? ('🎯 Turma: ' . $post->turma) : '📢 Geral (Todas as Turmas)'),
                            'data_evento' => $post->data_evento ? $post->data_evento->format('d/m/Y') : null,
                        ];
                    @endphp
                    <!-- Social Card Post -->
                    <article class="bg-white rounded-3xl border border-[#DCE3EC] shadow-sm hover:shadow-md transition-shadow overflow-hidden {{ $post->isUrgente() ? 'border-l-8 border-l-[#D64545]' : '' }}">
                        <!-- Post Author Header -->
                        <div class="p-5 pb-3 flex items-center justify-between border-b border-slate-50">
                            <div class="flex items-center gap-3">
                                <div class="w-11 h-11 rounded-2xl flex items-center justify-center font-extrabold text-sm text-white shadow-sm"
                                     style="background: linear-gradient(135deg, {{ $badgeColor }} 0%, #0B1F3B 100%);">
                                    {{ strtoupper(substr($post->autor->name ?? 'F', 0, 1)) }}
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h4 class="text-sm font-bold text-slate-900 leading-tight">
                                            {{ $post->autor->name ?? 'Coordenação FATEC' }}
                                        </h4>
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600">
                                            {{ $post->autor->role_label ?? 'Docente' }}
                                        </span>
                                    </div>
                                    <p class="text-[11px] text-slate-400 mt-0.5">
                                        Publicado {{ $post->created_at->diffForHumans() }} • {{ $post->created_at->format('d/m/Y H:i') }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold"
                                      style="background-color: {{ $badgeColor }}15; color: {{ $badgeColor }};">
                                    {{ $post->categoria }}
                                </span>
                            </div>
                        </div>

                        <!-- Post Body -->
                        <div class="p-6">
                            <div class="flex items-center gap-2 mb-2">
                                @if($post->turmaRelacionada)
                                    <span class="px-2.5 py-0.5 rounded-md text-[11px] font-bold bg-teal-50 text-[#19707F] border border-[#19707F]/20">
                                        🎯 Turma: {{ $post->turmaRelacionada->codigo }}
                                    </span>
                                @elseif($post->turma)
                                    <span class="px-2.5 py-0.5 rounded-md text-[11px] font-bold bg-teal-50 text-[#19707F] border border-[#19707F]/20">
                                        🎯 Turma: {{ $post->turma }}
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-md text-[11px] font-medium bg-slate-100 text-slate-500">
                                        📢 Geral (Todas as turmas)
                                    </span>
                                @endif

                                @if($post->isUrgente())
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-[#D64545] text-white uppercase tracking-wider">
                                        URGENTE
                                    </span>
                                @endif
                            </div>

                            <h3 class="text-lg font-bold text-slate-900 leading-snug mb-3 cursor-pointer hover:text-[#0097b2] transition-colors"
                                onclick="openPostModal({{ json_encode($postPayload) }})">
                                {{ $post->titulo }}
                            </h3>

                            <p class="text-sm text-slate-700 leading-relaxed whitespace-pre-line cursor-pointer"
                               onclick="openPostModal({{ json_encode($postPayload) }})">
                                {{ Str::limit($post->descricao, 280) }}
                            </p>
                        </div>

                        <!-- Post Footer Actions (Social Feed Style - Opens Modal) -->
                        <div class="px-6 py-3.5 bg-slate-50/80 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500 font-medium">
                            <div class="flex items-center gap-4">
                                <button type="button"
                                        onclick="openPostModal({{ json_encode($postPayload) }})"
                                        class="flex items-center gap-1.5 font-bold text-[#0097b2] hover:text-[#19707F] transition-colors cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    Abrir comunicado (Modal)
                                </button>
                            </div>

                            <span class="text-[11px] text-slate-400">
                                ID: #{{ $post->id }}
                            </span>
                        </div>
                    </article>
                @endforeach

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $comunicados->links() }}
                </div>
            @endif
        </div>

        <!-- Sidebar (Interactive Calendar & All Events) -->
        <div class="space-y-6">
            <!-- Interactive Calendar Component -->
            <div class="bg-white rounded-3xl p-6 border border-[#DCE3EC] shadow-sm">
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                    <h2 class="text-sm font-bold text-[#0B1F3B] flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#0097b2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Calendário de Eventos
                    </h2>
                    <div class="flex items-center gap-1">
                        <button type="button" onclick="sidePrevMonth()" class="p-1 rounded hover:bg-slate-100 text-slate-600 font-bold">‹</button>
                        <span id="side-cal-label" class="text-xs font-bold text-slate-700 min-w-[80px] text-center"></span>
                        <button type="button" onclick="sideNextMonth()" class="p-1 rounded hover:bg-slate-100 text-slate-600 font-bold">›</button>
                    </div>
                </div>

                <div class="grid grid-cols-7 gap-1 text-center text-[10px] font-bold text-slate-400 uppercase mb-2">
                    <span>D</span><span>S</span><span>T</span><span>Q</span><span>Q</span><span>S</span><span>S</span>
                </div>

                <div id="side-calendar-days" class="grid grid-cols-7 gap-1 text-center text-xs">
                    <!-- Populated by JS -->
                </div>

                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                    <span class="text-slate-500">Agenda Oficial</span>
                    <a href="{{ route('eventos.index') }}" class="font-bold text-[#0097b2] hover:underline">
                        Ver todos os eventos →
                    </a>
                </div>
            </div>

            <!-- All Events List Widget -->
            <div class="bg-white rounded-3xl p-6 border border-[#DCE3EC] shadow-sm">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                    <h3 class="text-sm font-bold text-slate-900">
                        Eventos & Visitas Técnicas
                    </h3>
                    <a href="{{ route('eventos.index') }}" class="text-xs font-bold text-[#0097b2] hover:underline">
                        Agenda Completa
                    </a>
                </div>

                <div class="space-y-3">
                    @forelse($todosEventos as $evento)
                        @php
                            $isHighlight = str_contains($evento->data->format('Y-m-d'), '10-25');
                        @endphp
                        <div class="p-3 rounded-2xl border {{ $isHighlight ? 'border-2 border-[#0097b2] bg-teal-50/40 shadow-sm' : 'border-slate-100 bg-slate-50/60' }} hover:border-teal-200 transition-colors">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-xl {{ $isHighlight ? 'bg-[#0097b2]' : 'bg-[#19707F]' }} text-white flex flex-col items-center justify-center shrink-0 shadow-sm">
                                    <span class="text-xs font-extrabold leading-none">{{ $evento->data->format('d') }}</span>
                                    <span class="text-[9px] font-bold uppercase leading-none mt-0.5">{{ $evento->data->translatedFormat('M') }}</span>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center gap-1.5">
                                        <h4 class="text-xs font-bold text-slate-900 truncate">
                                            {{ $evento->titulo }}
                                        </h4>
                                        @if($isHighlight)
                                            <span class="px-1.5 py-0.2 rounded text-[9px] font-extrabold bg-[#0097b2] text-white">25/10</span>
                                        @endif
                                    </div>
                                    <p class="text-[11px] text-slate-500 mt-0.5">
                                        {{ substr($evento->horario, 0, 5) }} • {{ $evento->local }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 text-center py-4">Nenhum evento registrado.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ================= MODAL: VISUALIZAÇÃO DE COMUNICADO (FEED IN-PLACE) ================= -->
<div id="modal-ver-comunicado" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-2xl w-full p-6 sm:p-8 shadow-2xl max-h-[90vh] overflow-y-auto transform transition-all relative">
        <div class="flex items-start justify-between gap-4 pb-4 border-b border-slate-100 mb-6">
            <div class="flex items-center gap-3">
                <div id="modal-post-author-avatar" class="w-12 h-12 rounded-2xl bg-gradient-to-br from-[#0097b2] to-[#0B1F3B] text-white flex items-center justify-center font-bold text-base shadow-sm">
                    F
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h4 id="modal-post-author-name" class="text-base font-bold text-slate-900"></h4>
                        <span id="modal-post-author-role" class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600"></span>
                    </div>
                    <p id="modal-post-date" class="text-xs text-slate-400 mt-0.5"></p>
                </div>
            </div>
            <button type="button" onclick="closePostModal()" class="w-9 h-9 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-800 flex items-center justify-center transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="space-y-4">
            <!-- Badges Row -->
            <div class="flex flex-wrap items-center gap-2">
                <span id="modal-post-category" class="px-3 py-1 rounded-full text-xs font-bold"></span>
                <span id="modal-post-urgent" class="hidden px-2.5 py-1 rounded-full text-[11px] font-extrabold bg-[#D64545] text-white uppercase tracking-wider">
                    URGENTE
                </span>
                <span id="modal-post-turma" class="px-3 py-1 rounded-lg text-xs font-semibold bg-teal-50 text-[#19707F] border border-[#19707F]/20"></span>
            </div>

            <!-- Title -->
            <h2 id="modal-post-title" class="text-xl sm:text-2xl font-extrabold text-slate-900 leading-snug"></h2>

            <!-- Event Date Box (optional) -->
            <div id="modal-post-event-box" class="hidden p-3.5 rounded-2xl bg-teal-50 border border-[#0097b2]/30 flex items-center gap-3 text-xs text-[#19707F] font-semibold">
                <svg class="w-5 h-5 text-[#0097b2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span>Data / Prazo Relacionado: <strong id="modal-post-event-date" class="font-bold text-slate-900"></strong></span>
            </div>

            <!-- Full Description Content -->
            <div class="pt-2 border-t border-slate-100">
                <p id="modal-post-descricao" class="text-sm sm:text-base text-slate-700 leading-relaxed whitespace-pre-line"></p>
            </div>
        </div>

        <div class="mt-8 pt-4 border-t border-slate-100 flex items-center justify-between">
            <span class="text-xs text-slate-400">Plataforma Nexus FATEC • Central do Estudante</span>
            <button type="button" onclick="closePostModal()" class="px-5 py-2.5 rounded-xl bg-[#0B1F3B] hover:bg-[#142f56] text-white text-xs font-bold transition-colors">
                Fechar
            </button>
        </div>
    </div>
</div>

<script>
const allEvents = @json($eventosJson);

let sideYear = 2026;
let sideMonth = 9; // October (0-indexed: 9)

const monthLabels = [
    'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho',
    'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'
];

function renderSideCalendar() {
    const label = document.getElementById('side-cal-label');
    label.innerText = `${monthLabels[sideMonth]} ${sideYear}`;

    const container = document.getElementById('side-calendar-days');
    container.innerHTML = '';

    const firstDay = new Date(sideYear, sideMonth, 1).getDay();
    const daysInMonth = new Date(sideYear, sideMonth + 1, 0).getDate();

    for (let i = 0; i < firstDay; i++) {
        const blank = document.createElement('div');
        blank.className = 'py-1.5';
        container.appendChild(blank);
    }

    for (let day = 1; day <= daysInMonth; day++) {
        const dayStr = `${sideYear}-${String(sideMonth + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
        const dayEvents = allEvents.filter(e => e.data === dayStr);

        const btn = document.createElement('a');
        btn.href = "{{ route('eventos.index') }}";
        btn.className = `py-1.5 rounded-lg text-xs font-semibold transition-all relative flex flex-col items-center justify-center ${
            dayEvents.length > 0 
                ? 'bg-[#0097b2]/20 text-[#0097b2] font-extrabold border border-[#0097b2]/40 hover:bg-[#0097b2] hover:text-white' 
                : 'text-slate-600 hover:bg-slate-100'
        }`;
        btn.innerText = day;

        if (dayEvents.length > 0) {
            const dot = document.createElement('span');
            dot.className = 'w-1 h-1 rounded-full bg-current mt-0.5';
            btn.appendChild(dot);
            btn.title = dayEvents.map(e => e.titulo).join(' | ');
        }

        container.appendChild(btn);
    }
}

function sidePrevMonth() {
    sideMonth--;
    if (sideMonth < 0) {
        sideMonth = 11;
        sideYear--;
    }
    renderSideCalendar();
}

function sideNextMonth() {
    sideMonth++;
    if (sideMonth > 11) {
        sideMonth = 0;
        sideYear++;
    }
    renderSideCalendar();
}

function filterTurma(turmaId) {
    const url = new URL(window.location.href);
    if (turmaId) {
        url.searchParams.set('turma_id', turmaId);
    } else {
        url.searchParams.delete('turma_id');
    }
    window.location.href = url.toString();
}

// Modal handling
function openPostModal(data) {
    document.getElementById('modal-post-title').innerText = data.titulo;
    document.getElementById('modal-post-author-name').innerText = data.autor_name;
    document.getElementById('modal-post-author-role').innerText = data.autor_role;
    document.getElementById('modal-post-author-avatar').innerText = data.autor_initial;
    document.getElementById('modal-post-date').innerText = data.date_formatted;
    document.getElementById('modal-post-category').innerText = data.categoria;
    document.getElementById('modal-post-category').style.backgroundColor = data.categoria_color + '20';
    document.getElementById('modal-post-category').style.color = data.categoria_color;
    
    // Turma
    document.getElementById('modal-post-turma').innerText = data.turma_label;
    
    // Urgente badge
    const urgentBadge = document.getElementById('modal-post-urgent');
    if (data.is_urgente) {
        urgentBadge.classList.remove('hidden');
    } else {
        urgentBadge.classList.add('hidden');
    }

    // Data relacionada / prazo
    const eventBox = document.getElementById('modal-post-event-box');
    if (data.data_evento) {
        eventBox.classList.remove('hidden');
        document.getElementById('modal-post-event-date').innerText = data.data_evento;
    } else {
        eventBox.classList.add('hidden');
    }

    // Descrição completa
    document.getElementById('modal-post-descricao').innerText = data.descricao;

    document.getElementById('modal-ver-comunicado').classList.remove('hidden');
}

function closePostModal() {
    document.getElementById('modal-ver-comunicado').classList.add('hidden');
}

// Close modal on Escape or backdrop click
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        closePostModal();
    }
});

document.getElementById('modal-ver-comunicado').addEventListener('click', (e) => {
    if (e.target.id === 'modal-ver-comunicado') {
        closePostModal();
    }
});

document.addEventListener('DOMContentLoaded', () => {
    renderSideCalendar();
});
</script>
@endsection
