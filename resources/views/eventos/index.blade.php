@extends('layouts.app')

@section('title', 'Agenda Completa de Eventos — Nexus FATEC')

@section('content')
<div class="py-8 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="bg-gradient-to-r from-[#0B1F3B] via-[#19707F] to-[#0097b2] rounded-3xl p-6 sm:p-8 text-white shadow-lg mb-8 relative overflow-hidden">
        <div class="max-w-3xl relative z-10">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-white/15 backdrop-blur-sm text-white mb-3">
                <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                Agenda Oficial FATEC Itaquera
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">
                Eventos, Atividades e Calendário Acadêmico
            </h1>
            <p class="text-teal-100 text-sm sm:text-base mt-1">
                Visualização completa de todos os eventos criados por professores e coordenação.
            </p>

            <!-- Search form -->
            <form action="{{ route('eventos.index') }}" method="GET" class="mt-6 flex gap-2">
                <input type="text"
                       name="q"
                       value="{{ $search ?? '' }}"
                       placeholder="Buscar evento por título, local ou descrição..."
                       class="w-full px-4 py-3 rounded-2xl bg-white text-slate-800 placeholder-slate-400 text-sm font-medium focus:outline-none shadow-md">
                <button type="submit" class="px-6 py-3 rounded-2xl bg-[#0B1F3B] hover:bg-[#142f56] text-white text-xs font-bold transition-colors shrink-0">
                    Buscar
                </button>
            </form>
        </div>
    </div>

    <!-- Main Grid: Calendar on Left/Top + All Events List -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Interactive Calendar Widget (1 col) -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-3xl p-6 border border-[#DCE3EC] shadow-sm">
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                    <h2 class="text-base font-bold text-[#0B1F3B] flex items-center gap-2">
                        <svg class="w-5 h-5 text-[#0097b2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Calendário FATEC
                    </h2>
                    <div class="flex items-center gap-1">
                        <button type="button" onclick="prevMonth()" class="p-1 rounded-lg hover:bg-slate-100 text-slate-600">‹</button>
                        <span id="cal-month-label" class="text-xs font-bold text-slate-700 min-w-[90px] text-center"></span>
                        <button type="button" onclick="nextMonth()" class="p-1 rounded-lg hover:bg-slate-100 text-slate-600">›</button>
                    </div>
                </div>

                <!-- Calendar Days Header -->
                <div class="grid grid-cols-7 gap-1 text-center text-[11px] font-bold text-slate-400 uppercase mb-2">
                    <span>Dom</span><span>Seg</span><span>Ter</span><span>Qua</span><span>Qui</span><span>Sex</span><span>Sáb</span>
                </div>

                <!-- Calendar Grid Container -->
                <div id="calendar-days" class="grid grid-cols-7 gap-1 text-center text-xs">
                    <!-- Populated by JS -->
                </div>

                <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500">
                    <span class="flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#0097b2]"></span>
                        Dias com Evento
                    </span>
                    <button type="button" onclick="showAllEvents()" class="text-[#0097b2] font-semibold hover:underline">
                        Limpar seleção
                    </button>
                </div>
            </div>

            <!-- Selected Date Info Box -->
            <div id="selected-date-box" class="bg-gradient-to-br from-[#19707F] to-[#0097b2] rounded-3xl p-6 text-white shadow-md hidden">
                <h3 class="text-xs font-bold uppercase tracking-wider text-teal-200 mb-1">Eventos no dia selecionado:</h3>
                <h4 id="selected-date-title" class="text-lg font-extrabold mb-3"></h4>
                <div id="selected-date-events" class="space-y-2 text-xs"></div>
            </div>
        </div>

        <!-- All Events List (2 cols) -->
        <div class="lg:col-span-2">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-[#0B1F3B] flex items-center gap-2">
                    <span>Todos os Eventos Cadastrados</span>
                    <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-teal-100 text-teal-800">
                        {{ $eventos->total() }} no total
                    </span>
                </h2>
                <span class="text-xs text-slate-500">Ordenado por data cronológica</span>
            </div>

            @if($eventos->isEmpty())
                <div class="bg-white rounded-3xl p-12 text-center border border-[#DCE3EC]">
                    <p class="text-sm font-bold text-slate-700">Nenhum evento encontrado.</p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($eventos as $ev)
                        @php
                            $isHighlight = str_contains($ev->data->format('Y-m-d'), '10-25');
                        @endphp
                        <div class="bg-white rounded-2xl p-5 border {{ $isHighlight ? 'border-2 border-[#0097b2] shadow-md' : 'border-[#DCE3EC]' }} hover:shadow-lg transition-all flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div class="flex items-start gap-4">
                                <!-- Date badge -->
                                <div class="w-16 h-16 rounded-2xl {{ $isHighlight ? 'bg-[#0097b2]' : 'bg-[#19707F]' }} text-white flex flex-col items-center justify-center shrink-0 shadow-md">
                                    <span class="text-xl font-extrabold leading-none">{{ $ev->data->format('d') }}</span>
                                    <span class="text-[10px] font-bold uppercase tracking-wider leading-none mt-1">{{ $ev->data->translatedFormat('M / Y') }}</span>
                                </div>

                                <div>
                                    <div class="flex items-center gap-2">
                                        <h3 class="font-bold text-slate-900 text-base">
                                            {{ $ev->titulo }}
                                        </h3>
                                        @if($isHighlight)
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-[#0097b2]/15 text-[#0097b2] uppercase">
                                                Destaque 25/10
                                            </span>
                                        @endif
                                    </div>

                                    <p class="text-slate-600 text-xs mt-1.5 leading-relaxed">
                                        {{ $ev->descricao }}
                                    </p>

                                    <div class="flex flex-wrap items-center gap-4 mt-3 text-xs text-slate-500 font-medium">
                                        <span class="flex items-center gap-1.5 text-slate-700">
                                            <svg class="w-4 h-4 text-[#0097b2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            {{ substr($ev->horario, 0, 5) }}
                                        </span>
                                        <span class="flex items-center gap-1.5 text-slate-700">
                                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            </svg>
                                            {{ $ev->local }}
                                        </span>
                                        <span class="text-slate-400">
                                            Criado por: {{ $ev->autor->name ?? 'FATEC' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $eventos->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<script>
const eventList = @json($eventosJson);

let currentYear = 2026;
let currentMonth = 9; // 0-indexed: 9 = October

const monthNames = [
    'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho',
    'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'
];

function renderCalendar() {
    const label = document.getElementById('cal-month-label');
    label.innerText = `${monthNames[currentMonth]} ${currentYear}`;

    const container = document.getElementById('calendar-days');
    container.innerHTML = '';

    const firstDay = new Date(currentYear, currentMonth, 1).getDay();
    const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();

    // Blank cells before first day
    for (let i = 0; i < firstDay; i++) {
        const blank = document.createElement('div');
        blank.className = 'py-2';
        container.appendChild(blank);
    }

    // Days in current month
    for (let day = 1; day <= daysInMonth; day++) {
        const dayStr = `${currentYear}-${String(currentMonth + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
        const dayEvents = eventList.filter(e => e.data === dayStr);

        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = `py-2 rounded-xl font-medium transition-all relative flex flex-col items-center justify-center ${
            dayEvents.length > 0 
                ? 'bg-[#0097b2]/15 text-[#0097b2] font-bold border border-[#0097b2]/40 hover:bg-[#0097b2] hover:text-white' 
                : 'text-slate-600 hover:bg-slate-100'
        }`;
        btn.innerText = day;

        if (dayEvents.length > 0) {
            const dot = document.createElement('span');
            dot.className = 'w-1.5 h-1.5 rounded-full bg-current mt-0.5';
            btn.appendChild(dot);

            btn.onclick = () => selectDate(dayStr, dayEvents);
        }

        container.appendChild(btn);
    }
}

function prevMonth() {
    currentMonth--;
    if (currentMonth < 0) {
        currentMonth = 11;
        currentYear--;
    }
    renderCalendar();
}

function nextMonth() {
    currentMonth++;
    if (currentMonth > 11) {
        currentMonth = 0;
        currentYear++;
    }
    renderCalendar();
}

function selectDate(dateStr, events) {
    const box = document.getElementById('selected-date-box');
    const title = document.getElementById('selected-date-title');
    const list = document.getElementById('selected-date-events');

    box.classList.remove('hidden');
    title.innerText = events[0].dataFormatada;
    list.innerHTML = '';

    events.forEach(ev => {
        const item = document.createElement('div');
        item.className = 'p-2.5 rounded-xl bg-white/10 border border-white/20';
        item.innerHTML = `<strong>${ev.titulo}</strong><br><span class="text-teal-100">${ev.horario} • ${ev.local}</span>`;
        list.appendChild(item);
    });
}

function showAllEvents() {
    document.getElementById('selected-date-box').classList.add('hidden');
}

document.addEventListener('DOMContentLoaded', () => {
    // If October has events, start in October
    renderCalendar();
});
</script>
@endsection
