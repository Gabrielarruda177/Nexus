<?php

namespace App\Http\Controllers;

use App\Models\Comunicado;
use App\Models\Evento;
use App\Models\Turma;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ComunicadoController extends Controller
{
    /**
     * Display student consultation feed (Social Network Style).
     */
    public function index(Request $request): View
    {
        $selectedCategory = $request->query('categoria');
        $selectedTurma = $request->query('turma_id');
        $searchQuery = $request->query('q');

        $query = Comunicado::with(['autor', 'turmaRelacionada'])->latest();

        if ($selectedCategory) {
            $query->where('categoria', $selectedCategory);
        }

        if ($selectedTurma) {
            $query->where('turma_id', $selectedTurma);
        }

        if ($searchQuery) {
            $query->where(function ($q) use ($searchQuery) {
                $q->where('titulo', 'like', "%{$searchQuery}%")
                    ->orWhere('descricao', 'like', "%{$searchQuery}%")
                    ->orWhere('categoria', 'like', "%{$searchQuery}%")
                    ->orWhere('turma', 'like', "%{$searchQuery}%");
            });
        }

        $comunicados = $query->paginate(10)->withQueryString();

        // Urgent announcements (shown on top when no search is active)
        $urgentes = Comunicado::with(['autor', 'turmaRelacionada'])
            ->where(function ($q) {
                $q->where('importancia', 'urgente')
                    ->orWhere('categoria', 'Urgente');
            })
            ->latest()
            ->take(3)
            ->get();

        // TODOS os eventos cadastrados (sem filtrar somente data >= hoje, para garantir visibilidade total)
        $todosEventos = Evento::with('autor')
            ->orderBy('data', 'asc')
            ->orderBy('horario', 'asc')
            ->get();

        $eventosJson = $todosEventos->map(fn ($ev) => [
            'id' => $ev->id,
            'titulo' => $ev->titulo,
            'data' => $ev->data->format('Y-m-d'),
            'dataFormatada' => $ev->data->format('d/m/Y'),
            'horario' => substr($ev->horario, 0, 5),
            'local' => $ev->local,
            'descricao' => $ev->descricao,
            'autor' => $ev->autor->name ?? 'FATEC',
        ]);

        $turmas = Turma::orderBy('codigo', 'asc')->get();

        $categorias = [
            'Acadêmico' => '#0097b2',
            'Eventos' => '#1C8C82',
            'Avisos' => '#D98C2B',
            'Turmas' => '#19707F',
            'Urgente' => '#D64545',
        ];

        return view('comunicados.index', compact(
            'comunicados',
            'urgentes',
            'todosEventos',
            'eventosJson',
            'turmas',
            'selectedCategory',
            'selectedTurma',
            'searchQuery',
            'categorias'
        ));
    }

    /**
     * Dedicated Events & Calendar page (/eventos).
     */
    public function eventos(Request $request): View
    {
        $search = $request->query('q');
        $query = Evento::with('autor')->orderBy('data', 'asc')->orderBy('horario', 'asc');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('titulo', 'like', "%{$search}%")
                    ->orWhere('descricao', 'like', "%{$search}%")
                    ->orWhere('local', 'like', "%{$search}%");
            });
        }

        $eventos = $query->paginate(12)->withQueryString();

        $todosEventos = Evento::with('autor')->orderBy('data', 'asc')->get();
        $eventosJson = $todosEventos->map(fn ($ev) => [
            'id' => $ev->id,
            'titulo' => $ev->titulo,
            'data' => $ev->data->format('Y-m-d'),
            'dataFormatada' => $ev->data->format('d/m/Y'),
            'horario' => substr($ev->horario, 0, 5),
            'local' => $ev->local,
            'descricao' => $ev->descricao,
        ]);

        return view('eventos.index', compact('eventos', 'todosEventos', 'eventosJson', 'search'));
    }

    /**
     * Display single comunicado details.
     */
    public function show(Comunicado $comunicado): View
    {
        $comunicado->load(['autor', 'turmaRelacionada']);

        $relacionados = Comunicado::with(['autor', 'turmaRelacionada'])
            ->where('categoria', $comunicado->categoria)
            ->where('id', '!=', $comunicado->id)
            ->latest()
            ->take(3)
            ->get();

        return view('comunicados.show', compact('comunicado', 'relacionados'));
    }

    /**
     * Dedicated search page (/busca?q=).
     */
    public function busca(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));

        $query = Comunicado::with(['autor', 'turmaRelacionada'])->latest();

        if (! empty($q)) {
            $query->where(function ($builder) use ($q) {
                $builder->where('titulo', 'like', "%{$q}%")
                    ->orWhere('descricao', 'like', "%{$q}%")
                    ->orWhere('categoria', 'like', "%{$q}%")
                    ->orWhere('turma', 'like', "%{$q}%");
            });
        }

        $comunicados = $query->paginate(12)->withQueryString();

        return view('comunicados.busca', [
            'q' => $q,
            'comunicados' => $comunicados,
        ]);
    }
}
