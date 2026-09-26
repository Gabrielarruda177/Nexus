@extends('layouts.app')

@section('title', 'Painel do Professor — Nexus FATEC')

@section('content')
<div class="py-8 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
    <!-- Header with Professor Persona Identity -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div class="flex items-center gap-3">
            <div class="w-11 h-11 rounded-2xl bg-[#19707F] text-white flex items-center justify-center shadow-md">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-extrabold text-[#0B1F3B] tracking-tight">
                    Painel do Professor
                </h1>
                <p class="text-xs text-slate-500 font-medium">
                    Olá, {{ auth()->user()->name }} • Gestão de comunicados, turmas e eventos acadêmicos
                </p>
            </div>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            <button type="button"
                    onclick="openModal('modal-novo-comunicado-prof')"
                    class="px-4 py-2.5 rounded-xl bg-[#0097b2] hover:bg-[#19707F] text-white text-xs font-bold shadow-sm flex items-center gap-1.5 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Novo Comunicado
            </button>
            <button type="button"
                    onclick="openModal('modal-novo-evento-prof')"
                    class="px-4 py-2.5 rounded-xl bg-white border border-[#DCE3EC] hover:bg-slate-50 text-slate-700 text-xs font-bold shadow-sm flex items-center gap-1.5 transition-colors">
                <svg class="w-4 h-4 text-[#1C8C82]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Organizar Evento
            </button>
        </div>
    </div>

    <!-- Quick Stats for Professor -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        <div class="bg-white rounded-2xl p-5 border border-[#DCE3EC] shadow-sm">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Meus Comunicados</span>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-3xl font-extrabold text-[#0B1F3B]">{{ $stats['meusComunicados'] }}</span>
                <span class="text-xs text-slate-400">avisos publicados</span>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-[#DCE3EC] shadow-sm">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Meus Eventos</span>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-3xl font-extrabold text-[#19707F]">{{ $stats['meusEventos'] }}</span>
                <span class="text-xs text-slate-400">atividades e palestras</span>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-[#DCE3EC] shadow-sm">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Avisos Urgentes Ativos</span>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-3xl font-extrabold text-rose-600">{{ $stats['totalUrgentes'] }}</span>
                <span class="text-xs text-slate-400">prioritários</span>
            </div>
        </div>
    </div>

    <!-- Tabs: Comunicados vs Eventos -->
    <div class="border-b border-slate-200 mb-6">
        <nav class="flex gap-4">
            <a href="{{ route('professor.dashboard', ['tab' => 'comunicados']) }}"
               class="pb-3 text-sm font-bold border-b-2 transition-colors {{ $tab === 'comunicados' ? 'border-[#0097b2] text-[#0097b2]' : 'border-transparent text-slate-500 hover:text-slate-700' }}">
                Meus Comunicados ({{ $stats['meusComunicados'] }})
            </a>
            <a href="{{ route('professor.dashboard', ['tab' => 'eventos']) }}"
               class="pb-3 text-sm font-bold border-b-2 transition-colors {{ $tab === 'eventos' ? 'border-[#0097b2] text-[#0097b2]' : 'border-transparent text-slate-500 hover:text-slate-700' }}">
                Meus Eventos Cadastrados ({{ $stats['meusEventos'] }})
            </a>
        </nav>
    </div>

    <!-- TAB 1: COMUNICADOS -->
    @if($tab === 'comunicados')
        <div class="bg-white rounded-3xl border border-[#DCE3EC] shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between flex-wrap gap-4">
                <div>
                    <h2 class="text-base font-bold text-slate-900">Comunicados Publicados por Você</h2>
                    <p class="text-xs text-slate-500">Seus alunos veem estes avisos diretamente no feed central e filtrados pela turma</p>
                </div>
                <button type="button"
                        onclick="openModal('modal-novo-comunicado-prof')"
                        class="px-4 py-2 rounded-xl bg-[#0097b2] hover:bg-[#19707F] text-white text-xs font-bold transition-colors">
                    + Criar Novo Comunicado
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-3.5">Título & Categoria</th>
                            <th class="px-6 py-3.5">Turma Direcionada</th>
                            <th class="px-6 py-3.5">Importância</th>
                            <th class="px-6 py-3.5">Data de Publicação</th>
                            <th class="px-6 py-3.5 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($comunicados as $c)
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-900 text-sm mb-1">{{ $c->titulo }}</div>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold"
                                          style="background-color: {{ $c->categoria_color }}15; color: {{ $c->categoria_color }};">
                                        {{ $c->categoria }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-slate-600 font-medium">
                                    @if($c->turmaRelacionada)
                                        <span class="px-2 py-0.5 rounded-md font-bold bg-teal-50 text-[#19707F]">
                                            {{ $c->turmaRelacionada->codigo }}
                                        </span>
                                    @elseif($c->turma)
                                        <span class="px-2 py-0.5 rounded-md font-medium bg-slate-100 text-slate-600">
                                            {{ $c->turma }}
                                        </span>
                                    @else
                                        <span class="text-slate-400 italic">Geral (Todas as Turmas)</span>
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
                                    {{ $c->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('comunicados.show', $c) }}" target="_blank" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100" title="Visualizar">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </a>
                                        <button type="button"
                                                onclick="openEditComunicadoProf({{ json_encode($c) }})"
                                                class="p-1.5 rounded-lg text-blue-600 hover:bg-blue-50" title="Editar">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>
                                        <form action="{{ route('professor.comunicados.destroy', $c) }}" method="POST" onsubmit="return confirm('Deseja realmente excluir este comunicado?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 rounded-lg text-rose-600 hover:bg-rose-50" title="Excluir">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-slate-400">
                                    Você ainda não publicou nenhum comunicado. Clique no botão acima para criar seu primeiro aviso.
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

    <!-- TAB 2: EVENTOS -->
    @if($tab === 'eventos')
        <div class="bg-white rounded-3xl border border-[#DCE3EC] shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between flex-wrap gap-4">
                <div>
                    <h2 class="text-base font-bold text-slate-900">Eventos e Atividades Cadastrados por Você</h2>
                    <p class="text-xs text-slate-500">Todos os eventos permanecem visíveis na agenda acadêmica dos estudantes</p>
                </div>
                <button type="button"
                        onclick="openModal('modal-novo-evento-prof')"
                        class="px-4 py-2 rounded-xl bg-[#19707F] hover:bg-[#1F464C] text-white text-xs font-bold transition-colors">
                    + Cadastrar Evento
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-3.5">Título do Evento</th>
                            <th class="px-6 py-3.5">Data & Horário</th>
                            <th class="px-6 py-3.5">Local</th>
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
                                <td class="px-6 py-4 text-slate-700 font-semibold">
                                    {{ $ev->data->format('d/m/Y') }} às {{ substr($ev->horario, 0, 5) }}
                                </td>
                                <td class="px-6 py-4 text-slate-600">
                                    <span class="bg-slate-100 px-2.5 py-1 rounded-md font-medium">
                                        {{ $ev->local }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button type="button"
                                                onclick="openEditEventoProf({{ json_encode($ev) }})"
                                                class="p-1.5 rounded-lg text-blue-600 hover:bg-blue-50" title="Editar">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>
                                        <form action="{{ route('professor.eventos.destroy', $ev) }}" method="POST" onsubmit="return confirm('Deseja realmente excluir este evento?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 rounded-lg text-rose-600 hover:bg-rose-50" title="Excluir">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-slate-400">
                                    Nenhum evento acadêmico cadastrado por você ainda.
                                </td>
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
</div>

<!-- ================= MODALS PROFESSOR ================= -->

<!-- Modal: Novo Comunicado Professor -->
<div id="modal-novo-comunicado-prof" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-xl w-full p-6 sm:p-8 shadow-2xl max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
            <h3 class="text-lg font-bold text-[#0B1F3B]">Criar Novo Comunicado</h3>
            <button type="button" onclick="closeModal('modal-novo-comunicado-prof')" class="text-slate-400 hover:text-slate-700">✕</button>
        </div>

        <form action="{{ route('professor.comunicados.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Título do Comunicado *</label>
                <input type="text" name="titulo" required placeholder="Ex: Alteração de Laboratório na aula de quarta" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-[#0097b2]">
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
                        <option value="">Geral (Todas as Turmas)</option>
                        @foreach($turmas as $t)
                            <option value="{{ $t->id }}">{{ $t->codigo }} - {{ $t->semestre }} ({{ $t->periodo }})</option>
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
                <textarea name="descricao" rows="4" required placeholder="Escreva os detalhes para os alunos..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-[#0097b2]"></textarea>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                <button type="button" onclick="closeModal('modal-novo-comunicado-prof')" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100">Cancelar</button>
                <button type="submit" class="px-5 py-2 rounded-xl text-xs font-bold bg-[#0097b2] text-white hover:bg-[#19707F]">Publicar Comunicado</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Editar Comunicado Professor -->
<div id="modal-edit-comunicado-prof" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-xl w-full p-6 sm:p-8 shadow-2xl max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
            <h3 class="text-lg font-bold text-[#0B1F3B]">Editar Comunicado</h3>
            <button type="button" onclick="closeModal('modal-edit-comunicado-prof')" class="text-slate-400 hover:text-slate-700">✕</button>
        </div>

        <form id="form-edit-comunicado-prof" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Título do Comunicado *</label>
                <input type="text" id="prof-edit-com-titulo" name="titulo" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-[#0097b2]">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Categoria *</label>
                    <select id="prof-edit-com-categoria" name="categoria" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-[#0097b2]">
                        <option value="Acadêmico">Acadêmico</option>
                        <option value="Eventos">Eventos</option>
                        <option value="Avisos">Avisos</option>
                        <option value="Turmas">Turmas</option>
                        <option value="Urgente">Urgente (Destaque Vermelho)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Importância *</label>
                    <select id="prof-edit-com-importancia" name="importancia" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-[#0097b2]">
                        <option value="normal">Normal</option>
                        <option value="importante">Importante</option>
                        <option value="urgente">Urgente</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Direcionar para Turma</label>
                    <select id="prof-edit-com-turma-id" name="turma_id" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-[#0097b2]">
                        <option value="">Geral (Todas as Turmas)</option>
                        @foreach($turmas as $t)
                            <option value="{{ $t->id }}">{{ $t->codigo }} - {{ $t->semestre }} ({{ $t->periodo }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Data Relacionada (Opcional)</label>
                    <input type="date" id="prof-edit-com-data" name="data_evento" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-[#0097b2]">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Descrição Completa *</label>
                <textarea id="prof-edit-com-descricao" name="descricao" rows="4" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-[#0097b2]"></textarea>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                <button type="button" onclick="closeModal('modal-edit-comunicado-prof')" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100">Cancelar</button>
                <button type="submit" class="px-5 py-2 rounded-xl text-xs font-bold bg-[#0097b2] text-white hover:bg-[#19707F]">Atualizar Comunicado</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Novo Evento Professor -->
<div id="modal-novo-evento-prof" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
            <h3 class="text-lg font-bold text-[#0B1F3B]">Cadastrar Novo Evento</h3>
            <button type="button" onclick="closeModal('modal-novo-evento-prof')" class="text-slate-400 hover:text-slate-700">✕</button>
        </div>

        <form action="{{ route('professor.eventos.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Título do Evento *</label>
                <input type="text" name="titulo" required placeholder="Ex: Workshop de Laravel & APIs" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-[#1C8C82]">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Data *</label>
                    <input type="date" name="data" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-[#1C8C82]">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Horário (HH:MM) *</label>
                    <input type="text" name="horario" placeholder="19:00" required pattern="[0-2][0-9]:[0-5][0-9]" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-[#1C8C82]">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Local / Sala *</label>
                <input type="text" name="local" required placeholder="Ex: Laboratório 02 (Bloco B)" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-[#1C8C82]">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Descrição Breve *</label>
                <textarea name="descricao" rows="3" required placeholder="Objetivo do evento, público-alvo e detalhes..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-[#1C8C82]"></textarea>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                <button type="button" onclick="closeModal('modal-novo-evento-prof')" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100">Cancelar</button>
                <button type="submit" class="px-5 py-2 rounded-xl text-xs font-bold bg-[#1C8C82] text-white hover:bg-[#167068]">Cadastrar Evento</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal(id) {
    document.getElementById(id).classList.remove('hidden');
}
function closeModal(id) {
    document.getElementById(id).classList.add('hidden');
}

function openEditComunicadoProf(c) {
    document.getElementById('form-edit-comunicado-prof').action = `/professor/comunicados/${c.id}`;
    document.getElementById('prof-edit-com-titulo').value = c.titulo;
    document.getElementById('prof-edit-com-categoria').value = c.categoria;
    document.getElementById('prof-edit-com-importancia').value = c.importancia;
    document.getElementById('prof-edit-com-turma-id').value = c.turma_id || '';
    document.getElementById('prof-edit-com-data').value = c.data_evento ? c.data_evento.split('T')[0] : '';
    document.getElementById('prof-edit-com-descricao').value = c.descricao;
    openModal('modal-edit-comunicado-prof');
}
</script>
@endsection
