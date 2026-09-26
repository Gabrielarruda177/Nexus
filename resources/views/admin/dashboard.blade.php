@extends('layouts.app')

@section('title', 'Painel do Administrador — Nexus FATEC')

@section('content')
<div class="py-8 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
    <!-- Header with Admin Identity -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div class="flex items-center gap-3">
            <div class="w-11 h-11 rounded-2xl bg-[#0B1F3B] text-white flex items-center justify-center shadow-md">
                <svg class="w-6 h-6 text-[#0097b2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-extrabold text-[#0B1F3B] tracking-tight">
                    Painel do Administrador
                </h1>
                <p class="text-xs text-slate-500 font-medium">
                    Supervisão de turmas, docentes, discentes e moderação institucional da FATEC
                </p>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="flex items-center gap-2 flex-wrap">
            <button type="button"
                    onclick="openModal('modal-novo-comunicado')"
                    class="px-4 py-2.5 rounded-xl bg-[#0B1F3B] hover:bg-[#142f56] text-white text-xs font-bold shadow-sm flex items-center gap-1.5 transition-colors">
                <svg class="w-4 h-4 text-[#0097b2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Novo Comunicado
            </button>
            <button type="button"
                    onclick="openModal('modal-nova-turma')"
                    class="px-4 py-2.5 rounded-xl bg-[#0097b2] hover:bg-[#19707F] text-white text-xs font-bold shadow-sm flex items-center gap-1.5 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                Criar Turma
            </button>
            <button type="button"
                    onclick="openModal('modal-novo-evento')"
                    class="px-4 py-2.5 rounded-xl bg-[#19707F] hover:bg-[#1F464C] text-white text-xs font-bold shadow-sm flex items-center gap-1.5 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Criar Evento
            </button>
        </div>
    </div>

    <!-- Overview Metrics Row -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 mb-8">
        <div class="bg-white rounded-2xl p-4 border border-[#DCE3EC] shadow-sm">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Comunicados</span>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-2xl font-extrabold text-[#0B1F3B]">{{ $stats['totalComunicados'] }}</span>
                @if($stats['totalUrgentes'] > 0)
                    <span class="text-[10px] font-bold text-rose-600 bg-rose-50 px-1.5 py-0.5 rounded-full">
                        {{ $stats['totalUrgentes'] }} urgentes
                    </span>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-2xl p-4 border border-[#DCE3EC] shadow-sm">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Turmas</span>
            <div class="mt-2">
                <span class="text-2xl font-extrabold text-[#0097b2]">{{ $stats['totalTurmas'] }}</span>
                <span class="text-[10px] text-slate-400 block mt-0.5">Semestres ativos</span>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-4 border border-[#DCE3EC] shadow-sm">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Professores</span>
            <div class="mt-2">
                <span class="text-2xl font-extrabold text-[#19707F]">{{ $stats['totalProfessores'] }}</span>
                <span class="text-[10px] text-slate-400 block mt-0.5">Corpo docente</span>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-4 border border-[#DCE3EC] shadow-sm">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Alunos</span>
            <div class="mt-2">
                <span class="text-2xl font-extrabold text-[#0B1F3B]">{{ $stats['totalAlunos'] }}</span>
                <span class="text-[10px] text-slate-400 block mt-0.5">Matriculados</span>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-4 border border-[#DCE3EC] shadow-sm">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Eventos</span>
            <div class="mt-2">
                <span class="text-2xl font-extrabold text-teal-600">{{ $stats['totalEventos'] }}</span>
                <span class="text-[10px] text-slate-400 block mt-0.5">Cadastrados no campus</span>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-4 border border-rose-200 shadow-sm">
            <span class="text-[11px] font-bold uppercase tracking-wider text-rose-400">🗑 Lixeira</span>
            <div class="mt-2">
                <span class="text-2xl font-extrabold text-rose-500">{{ $stats['totalLixeira'] }}</span>
                <span class="text-[10px] text-slate-400 block mt-0.5">Itens excluídos</span>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="border-b border-slate-200 mb-6">
        <nav class="flex gap-4 overflow-x-auto no-scrollbar">
            <a href="{{ route('admin.dashboard', ['tab' => 'comunicados']) }}"
               class="pb-3 text-sm font-bold border-b-2 transition-colors shrink-0 {{ $tab === 'comunicados' ? 'border-[#0B1F3B] text-[#0B1F3B]' : 'border-transparent text-slate-500 hover:text-slate-700' }}">
                Comunicados ({{ $stats['totalComunicados'] }})
            </a>
            <a href="{{ route('admin.dashboard', ['tab' => 'turmas']) }}"
               class="pb-3 text-sm font-bold border-b-2 transition-colors shrink-0 {{ $tab === 'turmas' ? 'border-[#0B1F3B] text-[#0B1F3B]' : 'border-transparent text-slate-500 hover:text-slate-700' }}">
                Turmas & Semestres ({{ $stats['totalTurmas'] }})
            </a>
            <a href="{{ route('admin.dashboard', ['tab' => 'professores']) }}"
               class="pb-3 text-sm font-bold border-b-2 transition-colors shrink-0 {{ $tab === 'professores' ? 'border-[#0B1F3B] text-[#0B1F3B]' : 'border-transparent text-slate-500 hover:text-slate-700' }}">
                Professores ({{ $stats['totalProfessores'] }})
            </a>
            <a href="{{ route('admin.dashboard', ['tab' => 'alunos']) }}"
               class="pb-3 text-sm font-bold border-b-2 transition-colors shrink-0 {{ $tab === 'alunos' ? 'border-[#0B1F3B] text-[#0B1F3B]' : 'border-transparent text-slate-500 hover:text-slate-700' }}">
                Alunos ({{ $stats['totalAlunos'] }})
            </a>
            <a href="{{ route('admin.dashboard', ['tab' => 'eventos']) }}"
               class="pb-3 text-sm font-bold border-b-2 transition-colors shrink-0 {{ $tab === 'eventos' ? 'border-[#0B1F3B] text-[#0B1F3B]' : 'border-transparent text-slate-500 hover:text-slate-700' }}">
                Todos os Eventos ({{ $stats['totalEventos'] }})
            </a>
            <a href="{{ route('admin.dashboard', ['tab' => 'lixeira']) }}"
               class="pb-3 text-sm font-bold border-b-2 transition-colors shrink-0 flex items-center gap-1.5 {{ $tab === 'lixeira' ? 'border-rose-500 text-rose-600' : 'border-transparent text-slate-500 hover:text-rose-500' }}">
                🗑 Lixeira
                @if($stats['totalLixeira'] > 0)
                    <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-rose-500 text-white text-[10px] font-bold">{{ $stats['totalLixeira'] }}</span>
                @endif
            </a>
        </nav>
    </div>

    <!-- TAB 1: COMUNICADOS -->
    @if($tab === 'comunicados')
        <div class="bg-white rounded-3xl border border-[#DCE3EC] shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between flex-wrap gap-4">
                <div>
                    <h2 class="text-base font-bold text-slate-900">Todos os Comunicados Publicados</h2>
                    <p class="text-xs text-slate-500">Avisos direcionados a turmas específicas ou a todo o campus</p>
                </div>
                <button type="button"
                        onclick="openModal('modal-novo-comunicado')"
                        class="px-4 py-2 rounded-xl bg-[#0B1F3B] hover:bg-[#142f56] text-white text-xs font-bold transition-colors">
                    + Publicar Comunicado
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-3.5">Título & Categoria</th>
                            <th class="px-6 py-3.5">Autor</th>
                            <th class="px-6 py-3.5">Turma Direcionada</th>
                            <th class="px-6 py-3.5">Importância</th>
                            <th class="px-6 py-3.5">Data</th>
                            <th class="px-6 py-3.5 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($comunicados as $c)
                            @php
                                $cPayload = [
                                    'id' => $c->id,
                                    'titulo' => $c->titulo,
                                    'descricao' => $c->descricao,
                                    'categoria' => $c->categoria,
                                    'categoria_color' => $c->categoria_color,
                                    'is_urgente' => $c->isUrgente(),
                                    'autor_name' => $c->autor->name ?? 'Coordenação FATEC',
                                    'autor_role' => $c->autor->role_label ?? 'Docente',
                                    'autor_initial' => strtoupper(substr($c->autor->name ?? 'C', 0, 1)),
                                    'date_formatted' => 'Publicado ' . $c->created_at->diffForHumans() . ' • ' . $c->created_at->format('d/m/Y H:i'),
                                    'turma_label' => $c->turmaRelacionada ? ('🎯 Turma: ' . $c->turmaRelacionada->label_completo) : ($c->turma ? ('🎯 Turma: ' . $c->turma) : '📢 Geral (Todas as Turmas)'),
                                    'data_evento' => $c->data_evento ? $c->data_evento->format('d/m/Y') : null,
                                ];
                            @endphp
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="px-6 py-4 cursor-pointer" onclick="openAdminViewComunicado({{ json_encode($cPayload) }})">
                                    <div class="font-bold text-slate-900 text-sm mb-1 hover:text-[#0097b2] transition-colors">{{ $c->titulo }}</div>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold"
                                          style="background-color: {{ $c->categoria_color }}15; color: {{ $c->categoria_color }};">
                                        {{ $c->categoria }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-slate-600 font-medium">
                                    {{ $c->autor->name ?? 'Coordenação' }}
                                </td>
                                <td class="px-6 py-4 text-slate-600">
                                    @if($c->turmaRelacionada)
                                        <span class="px-2 py-0.5 rounded-md font-bold bg-teal-50 text-[#19707F]">
                                            {{ $c->turmaRelacionada->codigo }}
                                        </span>
                                    @elseif($c->turma)
                                        <span class="px-2 py-0.5 rounded-md font-medium bg-slate-100 text-slate-600">
                                            {{ $c->turma }}
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-md text-slate-400 bg-slate-100">
                                            Geral (Todas)
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($c->isUrgente())
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#D64545] text-white uppercase">
                                            Urgente
                                        </span>
                                    @elseif($c->isImportante())
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">
                                            Importante
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-medium bg-slate-100 text-slate-600">
                                            Normal
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-slate-500">
                                    {{ $c->created_at->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button type="button"
                                                onclick="openAdminViewComunicado({{ json_encode($cPayload) }})"
                                                class="p-1.5 rounded-lg text-slate-500 hover:text-[#0097b2] hover:bg-slate-100 transition-colors" title="Visualizar Comunicado (Modal)">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </button>
                                        <button type="button"
                                                onclick="openEditComunicado({{ json_encode($c) }})"
                                                class="p-1.5 rounded-lg text-blue-600 hover:bg-blue-50" title="Editar">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>
                                        <form action="{{ route('admin.comunicados.destroy', $c) }}" method="POST" onsubmit="return confirm('Enviar para a lixeira?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 rounded-lg text-rose-600 hover:bg-rose-50" title="Mover para Lixeira">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-slate-400">
                                    Nenhum comunicado cadastrado.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-100">
                {{ $comunicados->links() }}
            </div>
        </div>
    @endif

    <!-- TAB 2: TURMAS -->
    @if($tab === 'turmas')
        <div class="bg-white rounded-3xl border border-[#DCE3EC] shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between flex-wrap gap-4">
                <div>
                    <h2 class="text-base font-bold text-slate-900">Gestão de Turmas e Semestres</h2>
                    <p class="text-xs text-slate-500">Crie turmas, vincule docentes e consulte discentes matriculados</p>
                </div>
                <button type="button"
                        onclick="openModal('modal-nova-turma')"
                        class="px-4 py-2 rounded-xl bg-[#0097b2] hover:bg-[#19707F] text-white text-xs font-bold transition-colors">
                    + Nova Turma
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-3.5">Código & Curso</th>
                            <th class="px-6 py-3.5">Semestre / Período</th>
                            <th class="px-6 py-3.5">Alunos</th>
                            <th class="px-6 py-3.5">Professores Vinculados</th>
                            <th class="px-6 py-3.5">Comunicados</th>
                            <th class="px-6 py-3.5 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($turmas as $turma)
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="px-6 py-4">
                                    <span class="font-extrabold text-sm text-[#0097b2] block">{{ $turma->codigo }}</span>
                                    <span class="text-slate-600 text-xs">{{ $turma->nome }}</span>
                                </td>
                                <td class="px-6 py-4 text-slate-700">
                                    <span class="font-semibold">{{ $turma->semestre }}</span> • {{ $turma->periodo }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700">
                                        {{ $turma->alunos_count }} alunos
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-1">
                                        @forelse($turma->professores as $prof)
                                            <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold bg-teal-50 text-teal-800">
                                                {{ $prof->name }}
                                            </span>
                                        @empty
                                            <span class="text-slate-400 italic">Nenhum</span>
                                        @endforelse
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-slate-500">
                                    {{ $turma->comunicados_count }} avisos
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button type="button"
                                                onclick="openEditTurma({{ json_encode($turma) }})"
                                                class="p-1.5 rounded-lg text-blue-600 hover:bg-blue-50" title="Editar">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>
                                        <form action="{{ route('admin.turmas.destroy', $turma) }}" method="POST" onsubmit="return confirm('Deseja realmente remover esta turma?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 rounded-lg text-rose-600 hover:bg-rose-50" title="Remover">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-slate-400">Nenhuma turma cadastrada.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-100">
                {{ $turmas->links() }}
            </div>
        </div>
    @endif

    <!-- TAB 3: PROFESSORES -->
    @if($tab === 'professores')
        <div class="bg-white rounded-3xl border border-[#DCE3EC] shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between flex-wrap gap-4">
                <div>
                    <h2 class="text-base font-bold text-slate-900">Corpo Docente da FATEC Itaquera</h2>
                    <p class="text-xs text-slate-500">Cadastre professores e vincule-os às suas turmas</p>
                </div>
                <button type="button"
                        onclick="openModal('modal-novo-professor')"
                        class="px-4 py-2 rounded-xl bg-[#19707F] hover:bg-[#1F464C] text-white text-xs font-bold transition-colors">
                    + Cadastrar Professor
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-3.5">Nome do Professor</th>
                            <th class="px-6 py-3.5">E-mail Institucional</th>
                            <th class="px-6 py-3.5">Turmas Lecionadas</th>
                            <th class="px-6 py-3.5">Posts</th>
                            <th class="px-6 py-3.5 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($professores as $p)
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="px-6 py-4 font-bold text-slate-900">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-7 h-7 rounded-full bg-teal-100 text-[#0097b2] flex items-center justify-center font-bold text-xs">
                                            {{ strtoupper(substr($p->name, 0, 1)) }}
                                        </div>
                                        <span>{{ $p->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-slate-600 font-medium">
                                    {{ $p->email }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-1">
                                        @forelse($p->turmasLecionadas as $tl)
                                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-teal-50 text-teal-800">
                                                {{ $tl->codigo }}
                                            </span>
                                        @empty
                                            <span class="text-slate-400 italic">Nenhuma</span>
                                        @endforelse
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-[#1B4B9C]">
                                        {{ $p->comunicados_count }} posts
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button type="button"
                                                onclick="openEditProfessor({{ json_encode($p) }}, {{ json_encode($p->turmasLecionadas->pluck('id')) }})"
                                                class="p-1.5 rounded-lg text-blue-600 hover:bg-blue-50" title="Editar">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>
                                        <form action="{{ route('admin.professores.destroy', $p) }}" method="POST" onsubmit="return confirm('Deseja realmente remover o professor {{ $p->name }}?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 rounded-lg text-rose-600 hover:bg-rose-50" title="Remover">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-slate-400">Nenhum professor cadastrado.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-100">
                {{ $professores->links() }}
            </div>
        </div>
    @endif

    <!-- TAB 4: ALUNOS -->
    @if($tab === 'alunos')
        <div class="bg-white rounded-3xl border border-[#DCE3EC] shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between flex-wrap gap-4">
                <div>
                    <h2 class="text-base font-bold text-slate-900">Alunos Matriculados</h2>
                    <p class="text-xs text-slate-500">Cadastre e gerencie estudantes e vincule-os à turma correspondente</p>
                </div>
                <button type="button"
                        onclick="openModal('modal-novo-aluno')"
                        class="px-4 py-2 rounded-xl bg-[#0097b2] hover:bg-[#19707F] text-white text-xs font-bold transition-colors">
                    + Cadastrar Aluno
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-3.5">Nome do Aluno</th>
                            <th class="px-6 py-3.5">E-mail Institucional</th>
                            <th class="px-6 py-3.5">Turma Matriculada</th>
                            <th class="px-6 py-3.5">Data Cadastro</th>
                            <th class="px-6 py-3.5 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($alunos as $a)
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="px-6 py-4 font-bold text-slate-900">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-7 h-7 rounded-full bg-cyan-100 text-[#0097b2] flex items-center justify-center font-bold text-xs">
                                            {{ strtoupper(substr($a->name, 0, 1)) }}
                                        </div>
                                        <span>{{ $a->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-slate-600 font-medium">
                                    {{ $a->email }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($a->turmaMatriculada)
                                        <span class="px-2 py-0.5 rounded-md font-bold bg-teal-50 text-teal-800">
                                            {{ $a->turmaMatriculada->codigo }} - {{ $a->turmaMatriculada->semestre }}
                                        </span>
                                    @else
                                        <span class="text-slate-400 italic">Não vinculada</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-slate-500">
                                    {{ $a->created_at->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button type="button"
                                                onclick="openEditAluno({{ json_encode($a) }})"
                                                class="p-1.5 rounded-lg text-blue-600 hover:bg-blue-50" title="Editar">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>
                                        <form action="{{ route('admin.alunos.destroy', $a) }}" method="POST" onsubmit="return confirm('Deseja realmente remover o aluno {{ $a->name }}?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 rounded-lg text-rose-600 hover:bg-rose-50" title="Remover">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-slate-400">Nenhum aluno cadastrado.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-100">
                {{ $alunos->links() }}
            </div>
        </div>
    @endif

    <!-- TAB 5: TODOS OS EVENTOS -->
    @if($tab === 'eventos')
        <div class="bg-white rounded-3xl border border-[#DCE3EC] shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between flex-wrap gap-4">
                <div>
                    <h2 class="text-base font-bold text-slate-900">Todos os Eventos Acadêmicos do Campus</h2>
                    <p class="text-xs text-slate-500">Visualização de todos os eventos sem filtro de data anterior/posterior</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('eventos.index') }}" target="_blank" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-colors">
                        Ver Calendário Público ↗
                    </a>
                    <button type="button"
                            onclick="openModal('modal-novo-evento')"
                            class="px-4 py-2 rounded-xl bg-[#0097b2] hover:bg-[#19707F] text-white text-xs font-bold transition-colors">
                        + Cadastrar Evento
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-3.5">Título do Evento</th>
                            <th class="px-6 py-3.5">Data & Horário</th>
                            <th class="px-6 py-3.5">Local</th>
                            <th class="px-6 py-3.5">Organizador</th>
                            <th class="px-6 py-3.5 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($eventos as $ev)
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-900 text-sm mb-0.5">{{ $ev->titulo }}</div>
                                    <p class="text-slate-500 text-[11px] line-clamp-1">{{ $ev->descricao }}</p>
                                </td>
                                <td class="px-6 py-4 text-slate-800 font-bold">
                                    {{ $ev->data->format('d/m/Y') }} às {{ substr($ev->horario, 0, 5) }}
                                </td>
                                <td class="px-6 py-4 text-slate-600">
                                    <span class="bg-slate-100 px-2.5 py-1 rounded-md font-medium">
                                        {{ $ev->local }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-slate-500 font-medium">
                                    {{ $ev->autor->name ?? 'FATEC' }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <form action="{{ route('admin.eventos.destroy', $ev) }}" method="POST" onsubmit="return confirm('Deseja realmente excluir este evento?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-lg text-rose-600 hover:bg-rose-50" title="Excluir">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-slate-400">Nenhum evento registrado.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-100">
                {{ $eventos->links() }}
            </div>
        </div>
    @endif

    {{-- TAB 6: LIXEIRA --}}
    @if($tab === 'lixeira')
        <div class="space-y-6">

            {{-- Banner de aviso --}}
            <div class="bg-rose-50 border border-rose-200 rounded-2xl p-4 flex items-center gap-3">
                <svg class="w-6 h-6 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <div>
                    <p class="text-sm font-bold text-rose-700">Área da Lixeira — Apenas Administradores</p>
                    <p class="text-xs text-rose-500 mt-0.5">Itens aqui foram removidos e <strong>não aparecem</strong> para alunos e professores. Você pode restaurá-los ou excluí-los permanentemente.</p>
                </div>
            </div>

            {{-- Comunicados na Lixeira --}}
            <div class="bg-white rounded-3xl border border-[#DCE3EC] shadow-sm overflow-hidden">
                <div class="p-5 border-b border-slate-100 flex items-center gap-3">
                    <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                    <h2 class="text-base font-bold text-slate-900">Comunicados Excluídos <span class="text-sm font-normal text-rose-500">({{ $lixeiraComunicados->count() }})</span></h2>
                </div>
                @forelse($lixeiraComunicados as $item)
                    <div class="px-6 py-4 border-b border-slate-100 last:border-0 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div>
                            <p class="text-sm font-bold text-slate-800">{{ $item->titulo }}</p>
                            <p class="text-xs text-slate-500 mt-0.5">{{ $item->categoria }} • Excluído {{ $item->deleted_at->diffForHumans() }}</p>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            <form action="{{ route('admin.comunicados.restore', $item->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition-colors flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                    Restaurar
                                </button>
                            </form>
                            <form id="force-com-{{ $item->id }}" action="{{ route('admin.comunicados.forceDelete', $item->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="confirmForceDelete('force-com-{{ $item->id }}')" class="px-3 py-1.5 rounded-lg border border-rose-300 text-rose-600 hover:bg-rose-50 text-xs font-bold transition-colors flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    Excluir
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-slate-400 text-sm">Nenhum comunicado na lixeira.</div>
                @endforelse
            </div>

            {{-- Usuários na Lixeira --}}
            <div class="bg-white rounded-3xl border border-[#DCE3EC] shadow-sm overflow-hidden">
                <div class="p-5 border-b border-slate-100 flex items-center gap-3">
                    <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <h2 class="text-base font-bold text-slate-900">Alunos & Professores Excluídos <span class="text-sm font-normal text-rose-500">({{ $lixeiraUsuarios->count() }})</span></h2>
                </div>
                @forelse($lixeiraUsuarios as $item)
                    <div class="px-6 py-4 border-b border-slate-100 last:border-0 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div>
                            <p class="text-sm font-bold text-slate-800">{{ $item->name }}</p>
                            <p class="text-xs text-slate-500 mt-0.5">{{ $item->email }} • <span class="capitalize">{{ $item->role_label }}</span> • Excluído {{ $item->deleted_at->diffForHumans() }}</p>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            <form action="{{ route('admin.usuarios.restore', $item->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition-colors flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                    Restaurar
                                </button>
                            </form>
                            <form id="force-usr-{{ $item->id }}" action="{{ route('admin.usuarios.forceDelete', $item->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="confirmForceDelete('force-usr-{{ $item->id }}')" class="px-3 py-1.5 rounded-lg border border-rose-300 text-rose-600 hover:bg-rose-50 text-xs font-bold transition-colors flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    Excluir
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-slate-400 text-sm">Nenhum usuário na lixeira.</div>
                @endforelse
            </div>

            {{-- Turmas na Lixeira --}}
            <div class="bg-white rounded-3xl border border-[#DCE3EC] shadow-sm overflow-hidden">
                <div class="p-5 border-b border-slate-100 flex items-center gap-3">
                    <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    <h2 class="text-base font-bold text-slate-900">Turmas Excluídas <span class="text-sm font-normal text-rose-500">({{ $lixeiraTurmas->count() }})</span></h2>
                </div>
                @forelse($lixeiraTurmas as $item)
                    <div class="px-6 py-4 border-b border-slate-100 last:border-0 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div>
                            <p class="text-sm font-bold text-slate-800">{{ $item->codigo }} — {{ $item->nome }}</p>
                            <p class="text-xs text-slate-500 mt-0.5">{{ $item->curso }} • {{ $item->semestre }} • {{ $item->periodo }} • Excluída {{ $item->deleted_at->diffForHumans() }}</p>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            <form action="{{ route('admin.turmas.restore', $item->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition-colors flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                    Restaurar
                                </button>
                            </form>
                            <form id="force-trm-{{ $item->id }}" action="{{ route('admin.turmas.forceDelete', $item->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="confirmForceDelete('force-trm-{{ $item->id }}')" class="px-3 py-1.5 rounded-lg border border-rose-300 text-rose-600 hover:bg-rose-50 text-xs font-bold transition-colors flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    Excluir
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-slate-400 text-sm">Nenhuma turma na lixeira.</div>
                @endforelse
            </div>

            {{-- Eventos na Lixeira --}}
            <div class="bg-white rounded-3xl border border-[#DCE3EC] shadow-sm overflow-hidden">
                <div class="p-5 border-b border-slate-100 flex items-center gap-3">
                    <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <h2 class="text-base font-bold text-slate-900">Eventos Excluídos <span class="text-sm font-normal text-rose-500">({{ $lixeiraEventos->count() }})</span></h2>
                </div>
                @forelse($lixeiraEventos as $item)
                    <div class="px-6 py-4 border-b border-slate-100 last:border-0 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div>
                            <p class="text-sm font-bold text-slate-800">{{ $item->titulo }}</p>
                            <p class="text-xs text-slate-500 mt-0.5">{{ $item->data->format('d/m/Y') }} às {{ $item->horario }} • {{ $item->local }} • Excluído {{ $item->deleted_at->diffForHumans() }}</p>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            <form action="{{ route('admin.eventos.restore', $item->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition-colors flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                    Restaurar
                                </button>
                            </form>
                            <form id="force-evt-{{ $item->id }}" action="{{ route('admin.eventos.forceDelete', $item->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="confirmForceDelete('force-evt-{{ $item->id }}')" class="px-3 py-1.5 rounded-lg border border-rose-300 text-rose-600 hover:bg-rose-50 text-xs font-bold transition-colors flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    Excluir
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-slate-400 text-sm">Nenhum evento na lixeira.</div>
                @endforelse
            </div>

        </div>
    @endif

</div>

<!-- ================= MODALS ================= -->

<!-- Modal: Novo Comunicado -->
<div id="modal-novo-comunicado" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-xl w-full p-6 sm:p-8 shadow-2xl max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
            <h3 class="text-lg font-bold text-[#0B1F3B]">Publicar Comunicado</h3>
            <button type="button" onclick="closeModal('modal-novo-comunicado')" class="text-slate-400 hover:text-slate-700">✕</button>
        </div>

        <form action="{{ route('admin.comunicados.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Título do Comunicado *</label>
                <input type="text" name="titulo" required placeholder="Ex: Alteração de Laboratório na Prova" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-[#0097b2]">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Categoria *</label>
                    <select name="categoria" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-[#0097b2]">
                        <option value="Acadêmico">Acadêmico</option>
                        <option value="Eventos">Eventos</option>
                        <option value="Avisos" selected>Avisos</option>
                        <option value="Turmas">Turmas</option>
                        <option value="Urgente">Urgente (Destaque Vermelho)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Importância *</label>
                    <select name="importancia" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-[#0097b2]">
                        <option value="normal" selected>Normal</option>
                        <option value="importante">Importante</option>
                        <option value="urgente">Urgente</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Direcionar para Turma</label>
                    <select name="turma_id" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-[#0097b2]">
                        <option value="">Geral (Todas as Turmas do Campus)</option>
                        @foreach($allTurmas as $turma)
                            <option value="{{ $turma->id }}">{{ $turma->codigo }} - {{ $turma->semestre }} ({{ $turma->periodo }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Data Relacionada (Opcional)</label>
                    <input type="date" name="data_evento" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-[#0097b2]">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Descrição Completa *</label>
                <textarea name="descricao" rows="4" required placeholder="Insira o texto detalhado do comunicado para os estudantes..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-[#0097b2]"></textarea>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                <button type="button" onclick="closeModal('modal-novo-comunicado')" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100">Cancelar</button>
                <button type="submit" class="px-5 py-2 rounded-xl text-xs font-bold bg-[#0B1F3B] text-white hover:bg-[#142f56]">Salvar e Publicar</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Nova Turma -->
<div id="modal-nova-turma" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
            <div>
                <h3 class="text-lg font-bold text-[#0B1F3B]">Criar Nova Turma</h3>
                <p class="text-xs text-slate-500">Cadastre a turma oficial para vincular alunos e professores</p>
            </div>
            <button type="button" onclick="closeModal('modal-nova-turma')" class="text-slate-400 hover:text-slate-700">✕</button>
        </div>

        <form action="{{ route('admin.turmas.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Curso Oficial FATEC Itaquera *</label>
                <select name="curso_nome" id="turma-curso-select" onchange="updateTurmaFields()" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-[#0097b2]">
                    <option value="">Selecione o Curso...</option>
                    <option value="Desenvolvimento de Software Multiplataforma" data-sigla="DSM" data-periodo="Tarde" selected>Desenvolvimento de Software Multiplataforma (Tarde)</option>
                    <option value="Automação Industrial" data-sigla="AUT" data-periodo="Tarde">Automação Industrial (Tarde • Noite)</option>
                    <option value="Fabricação Mecânica" data-sigla="FM" data-periodo="Noite">Fabricação Mecânica (Noite)</option>
                    <option value="Manutenção Industrial" data-sigla="MI" data-periodo="Matutino">Manutenção Industrial (Manhã)</option>
                    <option value="Mecânica - Processos de Soldagem" data-sigla="SOL" data-periodo="Noite">Mecânica - Processos de Soldagem (Noite)</option>
                    <option value="Refrigeração, Ventilação e Ar Condicionado" data-sigla="RAC" data-periodo="Matutino">Refrigeração, Ventilação e Ar Condicionado (Manhã • Noite)</option>
                </select>
                <input type="hidden" name="nome" id="turma-nome-input" value="Desenvolvimento de Software Multiplataforma - 1º Tarde">
                <input type="hidden" name="curso" id="turma-curso-input" value="Desenvolvimento de Software Multiplataforma">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Semestre *</label>
                    <select name="semestre" id="turma-semestre-select" onchange="updateTurmaFields()" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-[#0097b2]">
                        <option value="1º Semestre" selected>1º Semestre</option>
                        <option value="2º Semestre">2º Semestre</option>
                        <option value="3º Semestre">3º Semestre</option>
                        <option value="4º Semestre">4º Semestre</option>
                        <option value="5º Semestre">5º Semestre</option>
                        <option value="6º Semestre">6º Semestre</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Período *</label>
                    <select name="periodo" id="turma-periodo-select" onchange="updateTurmaFields()" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-[#0097b2]">
                        <option value="Tarde" selected>Tarde</option>
                        <option value="Noturno">Noturno</option>
                        <option value="Matutino">Matutino</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Código da Turma (Ex: DSM-1T) *</label>
                <input type="text" name="codigo" id="turma-codigo-input" value="DSM-1T" required placeholder="DSM-1T" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm font-bold uppercase focus:outline-none focus:ring-2 focus:ring-[#0097b2]">
                <p class="text-[11px] text-slate-400 mt-1">Preenchido automaticamente ao alterar os campos acima.</p>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Vincular Professores (Opcional)</label>
                <div class="space-y-1.5 max-h-28 overflow-y-auto p-2 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                    @foreach($professores as $prof)
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="professores[]" value="{{ $prof->id }}" class="rounded text-[#0097b2]">
                            <span>{{ $prof->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                <button type="button" onclick="closeModal('modal-nova-turma')" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100">Cancelar</button>
                <button type="submit" class="px-5 py-2 rounded-xl text-xs font-bold bg-[#0097b2] text-white hover:bg-[#19707F]">Cadastrar Turma</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Novo Evento -->
<div id="modal-novo-evento" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
            <h3 class="text-lg font-bold text-[#0B1F3B]">Cadastrar Evento Institucional</h3>
            <button type="button" onclick="closeModal('modal-novo-evento')" class="text-slate-400 hover:text-slate-700">✕</button>
        </div>

        <form action="{{ route('admin.eventos.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Título do Evento *</label>
                <input type="text" name="titulo" required placeholder="Ex: Feira de Tecnologia / Ida à Google" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-[#0097b2]">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Data *</label>
                    <input type="date" name="data" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-[#0097b2]">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Horário (HH:MM) *</label>
                    <input type="text" name="horario" placeholder="10:00" required pattern="[0-2][0-9]:[0-5][0-9]" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-[#0097b2]">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Local / Ponto de Encontro *</label>
                <input type="text" name="local" required placeholder="Ex: Auditório / Av. Paulista" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-[#0097b2]">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Descrição do Evento *</label>
                <textarea name="descricao" rows="3" required placeholder="Instruções para os participantes..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-[#0097b2]"></textarea>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                <button type="button" onclick="closeModal('modal-novo-evento')" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100">Cancelar</button>
                <button type="submit" class="px-5 py-2 rounded-xl text-xs font-bold bg-[#0097b2] text-white hover:bg-[#19707F]">Cadastrar Evento</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Novo Professor -->
<div id="modal-novo-professor" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
            <h3 class="text-lg font-bold text-[#0B1F3B]">Cadastrar Novo Professor</h3>
            <button type="button" onclick="closeModal('modal-novo-professor')" class="text-slate-400 hover:text-slate-700">✕</button>
        </div>

        <form action="{{ route('admin.professores.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Nome Completo *</label>
                <input type="text" name="name" required placeholder="Prof. Dr. Fulano de Tal" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-[#19707F]">
            </div>
            <div>
                <label class="block text-xs font-bold uppercase text-slate-700 mb-1">E-mail Institucional *</label>
                <input type="email" name="email" required placeholder="professor@fatec.sp.gov.br" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-[#19707F]">
            </div>
            <div>
                <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Vincular às Turmas</label>
                <div class="space-y-1.5 max-h-32 overflow-y-auto p-2 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                    @foreach($allTurmas as $turma)
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="turmas[]" value="{{ $turma->id }}" class="rounded text-[#0097b2]">
                            <span>{{ $turma->codigo }} - {{ $turma->nome }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Senha Inicial *</label>
                <input type="password" name="password" required value="prof123" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-[#19707F]">
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                <button type="button" onclick="closeModal('modal-novo-professor')" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100">Cancelar</button>
                <button type="submit" class="px-5 py-2 rounded-xl text-xs font-bold bg-[#19707F] text-white hover:bg-[#1F464C]">Cadastrar</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Novo Aluno -->
<div id="modal-novo-aluno" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
            <h3 class="text-lg font-bold text-[#0B1F3B]">Cadastrar Novo Aluno</h3>
            <button type="button" onclick="closeModal('modal-novo-aluno')" class="text-slate-400 hover:text-slate-700">✕</button>
        </div>

        <form action="{{ route('admin.alunos.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Nome Completo *</label>
                <input type="text" name="name" required placeholder="Nome do Aluno" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-[#0097b2]">
            </div>
            <div>
                <label class="block text-xs font-bold uppercase text-slate-700 mb-1">E-mail Institucional *</label>
                <input type="email" name="email" required placeholder="aluno@fatec.sp.gov.br" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-[#0097b2]">
            </div>
            <div>
                <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Turma Matriculada *</label>
                <select name="turma_id" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-[#0097b2]">
                    <option value="">Selecione a Turma...</option>
                    @foreach($allTurmas as $turma)
                        <option value="{{ $turma->id }}">{{ $turma->codigo }} - {{ $turma->semestre }} ({{ $turma->periodo }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Senha Inicial *</label>
                <input type="password" name="password" required value="aluno123" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-[#0097b2]">
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                <button type="button" onclick="closeModal('modal-novo-aluno')" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100">Cancelar</button>
                <button type="submit" class="px-5 py-2 rounded-xl text-xs font-bold bg-[#0097b2] text-white hover:bg-[#19707F]">Cadastrar Aluno</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Visualizar Comunicado no Admin (In-place Modal) -->
<div id="modal-ver-comunicado-admin" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-2xl w-full p-6 sm:p-8 shadow-2xl max-h-[90vh] overflow-y-auto transform transition-all relative">
        <div class="flex items-start justify-between gap-4 pb-4 border-b border-slate-100 mb-6">
            <div class="flex items-center gap-3">
                <div id="admin-modal-avatar" class="w-12 h-12 rounded-2xl bg-[#0B1F3B] text-white flex items-center justify-center font-bold text-base shadow-sm">
                    F
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h4 id="admin-modal-author-name" class="text-base font-bold text-slate-900"></h4>
                        <span id="admin-modal-author-role" class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600"></span>
                    </div>
                    <p id="admin-modal-date" class="text-xs text-slate-400 mt-0.5"></p>
                </div>
            </div>
            <button type="button" onclick="closeModal('modal-ver-comunicado-admin')" class="w-9 h-9 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-800 flex items-center justify-center transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="space-y-4">
            <div class="flex flex-wrap items-center gap-2">
                <span id="admin-modal-category" class="px-3 py-1 rounded-full text-xs font-bold"></span>
                <span id="admin-modal-urgent" class="hidden px-2.5 py-1 rounded-full text-[11px] font-extrabold bg-[#D64545] text-white uppercase tracking-wider">
                    URGENTE
                </span>
                <span id="admin-modal-turma" class="px-3 py-1 rounded-lg text-xs font-semibold bg-teal-50 text-[#19707F] border border-[#19707F]/20"></span>
            </div>

            <h2 id="admin-modal-title" class="text-xl sm:text-2xl font-extrabold text-slate-900 leading-snug"></h2>

            <div id="admin-modal-event-box" class="hidden p-3.5 rounded-2xl bg-teal-50 border border-[#0097b2]/30 flex items-center gap-3 text-xs text-[#19707F] font-semibold">
                <svg class="w-5 h-5 text-[#0097b2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span>Data / Prazo Relacionado: <strong id="admin-modal-event-date" class="font-bold text-slate-900"></strong></span>
            </div>

            <div class="pt-2 border-t border-slate-100">
                <p id="admin-modal-descricao" class="text-sm sm:text-base text-slate-700 leading-relaxed whitespace-pre-line"></p>
            </div>
        </div>

        <div class="mt-8 pt-4 border-t border-slate-100 flex items-center justify-between">
            <span class="text-xs text-slate-400">Moderação de Conteúdo • Admin Nexus FATEC</span>
            <button type="button" onclick="closeModal('modal-ver-comunicado-admin')" class="px-5 py-2.5 rounded-xl bg-[#0B1F3B] hover:bg-[#142f56] text-white text-xs font-bold transition-colors">
                Fechar
            </button>
        </div>
    </div>
</div>

<script>
function openModal(id) {
    document.getElementById(id).classList.remove('hidden');
}
function closeModal(id) {
    document.getElementById(id).classList.add('hidden');
}

function openAdminViewComunicado(data) {
    document.getElementById('admin-modal-title').innerText = data.titulo;
    document.getElementById('admin-modal-author-name').innerText = data.autor_name;
    document.getElementById('admin-modal-author-role').innerText = data.autor_role;
    document.getElementById('admin-modal-avatar').innerText = data.autor_initial;
    document.getElementById('admin-modal-date').innerText = data.date_formatted;
    document.getElementById('admin-modal-category').innerText = data.categoria;
    document.getElementById('admin-modal-category').style.backgroundColor = data.categoria_color + '20';
    document.getElementById('admin-modal-category').style.color = data.categoria_color;
    document.getElementById('admin-modal-turma').innerText = data.turma_label;

    const urgentBadge = document.getElementById('admin-modal-urgent');
    if (data.is_urgente) {
        urgentBadge.classList.remove('hidden');
    } else {
        urgentBadge.classList.add('hidden');
    }

    const eventBox = document.getElementById('admin-modal-event-box');
    if (data.data_evento) {
        eventBox.classList.remove('hidden');
        document.getElementById('admin-modal-event-date').innerText = data.data_evento;
    } else {
        eventBox.classList.add('hidden');
    }

    document.getElementById('admin-modal-descricao').innerText = data.descricao;
    openModal('modal-ver-comunicado-admin');
}

function updateTurmaFields() {
    const cursoSelect = document.getElementById('turma-curso-select');
    if (!cursoSelect) return;
    const selectedOption = cursoSelect.options[cursoSelect.selectedIndex];
    if (!selectedOption || !selectedOption.value) return;

    const cursoNome = selectedOption.value;
    const sigla = selectedOption.getAttribute('data-sigla') || 'DSM';
    const defaultPeriodo = selectedOption.getAttribute('data-periodo');
    
    const periodoSelect = document.getElementById('turma-periodo-select');
    if (defaultPeriodo && (!periodoSelect.value || periodoSelect.value === 'Tarde')) {
        periodoSelect.value = defaultPeriodo;
    }

    const semestre = document.getElementById('turma-semestre-select').value;
    const periodo = periodoSelect.value;

    const semNum = semestre.replace(/\D/g, '') || '1';
    let perLetter = 'T';
    if (periodo === 'Noturno' || periodo === 'Noite') perLetter = 'N';
    else if (periodo === 'Matutino' || periodo === 'Manhã') perLetter = 'M';

    document.getElementById('turma-codigo-input').value = `${sigla}-${semNum}${perLetter}`;
    document.getElementById('turma-curso-input').value = cursoNome;
    document.getElementById('turma-nome-input').value = `${cursoNome} - ${semNum}º ${periodo}`;
}

document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        const modals = ['modal-novo-comunicado', 'modal-nova-turma', 'modal-novo-evento', 'modal-novo-professor', 'modal-novo-aluno', 'modal-ver-comunicado-admin', 'modal-confirm-force-delete'];
        modals.forEach(id => {
            const m = document.getElementById(id);
            if (m && !m.classList.contains('hidden')) {
                closeModal(id);
            }
        });
    }
});

// ========== LIXEIRA FORCE DELETE MODAL ==========
let _forceDeleteForm = null;

function confirmForceDelete(formId) {
    _forceDeleteForm = document.getElementById(formId);
    openModal('modal-confirm-force-delete');
}

function doForceDelete() {
    if (_forceDeleteForm) {
        _forceDeleteForm.submit();
    }
}
</script>

{{-- Modal: Confirm Permanent Delete --}}
<div id="modal-confirm-force-delete" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeModal('modal-confirm-force-delete')"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6 z-10">
        <div class="flex flex-col items-center text-center gap-3">
            <div class="w-14 h-14 rounded-full bg-rose-100 flex items-center justify-center">
                <svg class="w-7 h-7 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <h3 class="text-lg font-extrabold text-slate-900">Excluir Permanentemente?</h3>
            <p class="text-sm text-slate-500">Esta ação <strong>não pode ser desfeita</strong>. O item será removido definitivamente do sistema.</p>
            <div class="flex gap-3 mt-2 w-full">
                <button type="button" onclick="closeModal('modal-confirm-force-delete')" class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-bold text-slate-700 hover:bg-slate-50 transition-colors">Cancelar</button>
                <button type="button" onclick="doForceDelete()" class="flex-1 px-4 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-sm font-bold transition-colors">Sim, excluir</button>
            </div>
        </div>
    </div>
</div>

@endsection
