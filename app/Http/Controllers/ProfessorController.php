<?php

namespace App\Http\Controllers;

use App\Models\Comunicado;
use App\Models\Evento;
use App\Models\Turma;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfessorController extends Controller
{
    /**
     * Display Professor dashboard.
     */
    public function dashboard(Request $request): View
    {
        $user = Auth::user();
        $userId = $user->id;
        $tab = $request->query('tab', 'comunicados');

        $stats = [
            'meusComunicados' => Comunicado::where('autor_id', $userId)->count(),
            'meusEventos' => Evento::where('autor_id', $userId)->count(),
            'totalUrgentes' => Comunicado::where('autor_id', $userId)
                ->where(fn ($q) => $q->where('importancia', 'urgente')->orWhere('categoria', 'Urgente'))
                ->count(),
        ];

        $comunicados = Comunicado::with('turmaRelacionada')
            ->where('autor_id', $userId)
            ->latest()
            ->paginate(10, ['*'], 'comunicados_page');

        $eventos = Evento::where('autor_id', $userId)
            ->orderBy('data', 'asc')
            ->orderBy('horario', 'asc')
            ->paginate(10, ['*'], 'eventos_page');

        // Turmas que o professor leciona (ou todas se não vinculadas ainda)
        $turmas = $user->turmasLecionadas()->get();
        if ($turmas->isEmpty()) {
            $turmas = Turma::orderBy('codigo', 'asc')->get();
        }

        return view('professor.dashboard', compact('stats', 'comunicados', 'eventos', 'turmas', 'tab'));
    }

    /**
     * Store a new Comunicado created by Professor.
     */
    public function storeComunicado(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'titulo' => ['required', 'string', 'max:255'],
            'descricao' => ['required', 'string'],
            'categoria' => ['required', Rule::in(['Acadêmico', 'Eventos', 'Avisos', 'Turmas', 'Urgente'])],
            'turma_id' => ['nullable', 'exists:turmas,id'],
            'importancia' => ['required', Rule::in(['normal', 'importante', 'urgente'])],
            'data_evento' => ['nullable', 'date'],
        ], [
            'titulo.required' => 'O título do comunicado é obrigatório.',
            'descricao.required' => 'A descrição é obrigatória.',
            'categoria.required' => 'A categoria é obrigatória.',
        ]);

        if ($validated['categoria'] === 'Urgente') {
            $validated['importancia'] = 'urgente';
        }

        $turmaNome = null;
        if (! empty($validated['turma_id'])) {
            $t = Turma::find($validated['turma_id']);
            $turmaNome = $t?->label_completo;
        }

        Comunicado::create([
            ...$validated,
            'turma' => $turmaNome,
            'autor_id' => Auth::id(),
        ]);

        return redirect()->route('professor.dashboard', ['tab' => 'comunicados'])
            ->with('success', 'Comunicado criado com sucesso!');
    }

    /**
     * Update an existing Comunicado.
     */
    public function updateComunicado(Request $request, Comunicado $comunicado): RedirectResponse
    {
        if ($comunicado->autor_id !== Auth::id()) {
            abort(403, 'Você só pode editar comunicados criados por você.');
        }

        $validated = $request->validate([
            'titulo' => ['required', 'string', 'max:255'],
            'descricao' => ['required', 'string'],
            'categoria' => ['required', Rule::in(['Acadêmico', 'Eventos', 'Avisos', 'Turmas', 'Urgente'])],
            'turma_id' => ['nullable', 'exists:turmas,id'],
            'importancia' => ['required', Rule::in(['normal', 'importante', 'urgente'])],
            'data_evento' => ['nullable', 'date'],
        ]);

        if ($validated['categoria'] === 'Urgente') {
            $validated['importancia'] = 'urgente';
        }

        $turmaNome = null;
        if (! empty($validated['turma_id'])) {
            $t = Turma::find($validated['turma_id']);
            $turmaNome = $t?->label_completo;
        }

        $comunicado->update([
            ...$validated,
            'turma' => $turmaNome,
        ]);

        return redirect()->route('professor.dashboard', ['tab' => 'comunicados'])
            ->with('success', 'Comunicado atualizado com sucesso!');
    }

    /**
     * Delete an existing Comunicado.
     */
    public function destroyComunicado(Comunicado $comunicado): RedirectResponse
    {
        if ($comunicado->autor_id !== Auth::id()) {
            abort(403, 'Você só pode excluir comunicados criados por você.');
        }

        $comunicado->delete();

        return redirect()->route('professor.dashboard', ['tab' => 'comunicados'])
            ->with('success', 'Comunicado excluído com sucesso.');
    }

    /**
     * Store a new Evento created by Professor.
     */
    public function storeEvento(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'titulo' => ['required', 'string', 'max:255'],
            'descricao' => ['required', 'string'],
            'data' => ['required', 'date'],
            'horario' => ['required', 'date_format:H:i'],
            'local' => ['required', 'string', 'max:150'],
        ], [
            'titulo.required' => 'O título do evento é obrigatório.',
            'descricao.required' => 'A descrição do evento é obrigatória.',
            'data.required' => 'A data do evento é obrigatória.',
            'horario.required' => 'O horário do evento é obrigatório.',
            'local.required' => 'O local do evento é obrigatório.',
        ]);

        Evento::create([
            ...$validated,
            'autor_id' => Auth::id(),
        ]);

        return redirect()->route('professor.dashboard', ['tab' => 'eventos'])
            ->with('success', 'Evento cadastrado com sucesso!');
    }

    /**
     * Update an existing Evento.
     */
    public function updateEvento(Request $request, Evento $evento): RedirectResponse
    {
        if ($evento->autor_id !== Auth::id()) {
            abort(403, 'Você só pode editar eventos criados por você.');
        }

        $validated = $request->validate([
            'titulo' => ['required', 'string', 'max:255'],
            'descricao' => ['required', 'string'],
            'data' => ['required', 'date'],
            'horario' => ['required', 'date_format:H:i'],
            'local' => ['required', 'string', 'max:150'],
        ]);

        $evento->update($validated);

        return redirect()->route('professor.dashboard', ['tab' => 'eventos'])
            ->with('success', 'Evento atualizado com sucesso!');
    }

    /**
     * Delete an existing Evento.
     */
    public function destroyEvento(Evento $evento): RedirectResponse
    {
        if ($evento->autor_id !== Auth::id()) {
            abort(403, 'Você só pode excluir eventos criados por você.');
        }

        $evento->delete();

        return redirect()->route('professor.dashboard', ['tab' => 'eventos'])
            ->with('success', 'Evento excluído com sucesso.');
    }
}
