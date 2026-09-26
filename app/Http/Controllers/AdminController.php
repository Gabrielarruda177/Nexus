<?php

namespace App\Http\Controllers;

use App\Models\Comunicado;
use App\Models\Evento;
use App\Models\Turma;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function dashboard(Request $request): View
    {
        $stats = [
            'totalComunicados' => Comunicado::count(),
            'totalProfessores' => User::where('role', 'professor')->count(),
            'totalAlunos' => User::where('role', 'aluno')->count(),
            'totalTurmas' => Turma::count(),
            'totalEventos' => Evento::count(),
            'totalUrgentes' => Comunicado::where('importancia', 'urgente')->orWhere('categoria', 'Urgente')->count(),
            'totalLixeira' => Comunicado::onlyTrashed()->count()
                + Turma::onlyTrashed()->count()
                + User::onlyTrashed()->whereIn('role', ['aluno', 'professor'])->count()
                + Evento::onlyTrashed()->count(),
        ];

        $tab = $request->query('tab', 'comunicados');

        $comunicados = Comunicado::with(['autor', 'turmaRelacionada'])->latest()->paginate(10, ['*'], 'comunicados_page');
        $professores = User::where('role', 'professor')->with(['turmasLecionadas'])->withCount(['comunicados', 'eventos'])->latest()->paginate(10, ['*'], 'professores_page');
        $alunos = User::where('role', 'aluno')->with('turmaMatriculada')->latest()->paginate(10, ['*'], 'alunos_page');
        $turmas = Turma::withCount(['alunos', 'professores', 'comunicados'])->with('professores')->orderBy('codigo', 'asc')->paginate(10, ['*'], 'turmas_page');
        $eventos = Evento::with('autor')->orderBy('data', 'asc')->orderBy('horario', 'asc')->paginate(15, ['*'], 'eventos_page');
        $allTurmas = Turma::orderBy('codigo', 'asc')->get();
        $allProfessores = User::where('role', 'professor')->orderBy('name', 'asc')->get();

        // Lixeira
        $lixeiraComunicados = Comunicado::onlyTrashed()->with(['autor', 'turmaRelacionada'])->latest('deleted_at')->get();
        $lixeiraUsuarios = User::onlyTrashed()->whereIn('role', ['aluno', 'professor'])->latest('deleted_at')->get();
        $lixeiraTurmas = Turma::onlyTrashed()->latest('deleted_at')->get();
        $lixeiraEventos = Evento::onlyTrashed()->with('autor')->latest('deleted_at')->get();

        return view('admin.dashboard', compact(
            'stats', 'comunicados', 'professores', 'alunos', 'turmas', 'eventos',
            'allTurmas', 'allProfessores', 'tab',
            'lixeiraComunicados', 'lixeiraUsuarios', 'lixeiraTurmas', 'lixeiraEventos'
        ));
    }

    // ==================== TURMAS ====================

    public function storeTurma(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'codigo' => ['required', 'string', 'max:50', 'unique:turmas,codigo'],
            'curso' => ['required', 'string', 'max:100'],
            'semestre' => ['required', 'string', 'max:50'],
            'periodo' => ['required', 'string', 'max:50'],
            'descricao' => ['nullable', 'string'],
            'professores' => ['nullable', 'array'],
            'professores.*' => ['exists:users,id'],
        ], [
            'nome.required' => 'O nome da turma e obrigatorio.',
            'codigo.required' => 'O codigo da turma e obrigatorio.',
            'codigo.unique' => 'Ja existe uma turma com este codigo.',
        ]);

        $turma = Turma::create([
            'nome' => $validated['nome'],
            'codigo' => strtoupper($validated['codigo']),
            'curso' => $validated['curso'],
            'semestre' => $validated['semestre'],
            'periodo' => $validated['periodo'],
            'descricao' => $validated['descricao'] ?? null,
        ]);

        if (! empty($validated['professores'])) {
            $turma->professores()->sync($validated['professores']);
        }

        return redirect()->route('admin.dashboard', ['tab' => 'turmas'])->with('success', "Turma {$turma->codigo} cadastrada com sucesso!");
    }

    public function updateTurma(Request $request, Turma $turma): RedirectResponse
    {
        $validated = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'codigo' => ['required', 'string', 'max:50', Rule::unique('turmas')->ignore($turma->id)],
            'curso' => ['required', 'string', 'max:100'],
            'semestre' => ['required', 'string', 'max:50'],
            'periodo' => ['required', 'string', 'max:50'],
            'descricao' => ['nullable', 'string'],
            'professores' => ['nullable', 'array'],
            'professores.*' => ['exists:users,id'],
        ]);

        $turma->update([
            'nome' => $validated['nome'],
            'codigo' => strtoupper($validated['codigo']),
            'curso' => $validated['curso'],
            'semestre' => $validated['semestre'],
            'periodo' => $validated['periodo'],
            'descricao' => $validated['descricao'] ?? null,
        ]);

        if (isset($validated['professores'])) {
            $turma->professores()->sync($validated['professores']);
        }

        return redirect()->route('admin.dashboard', ['tab' => 'turmas'])->with('success', "Turma {$turma->codigo} atualizada com sucesso!");
    }

    public function destroyTurma(Turma $turma): RedirectResponse
    {
        $codigo = $turma->codigo;
        $turma->delete();

        return redirect()->route('admin.dashboard', ['tab' => 'turmas'])->with('success', "Turma {$codigo} enviada para a lixeira.");
    }

    public function restoreTurma(int $id): RedirectResponse
    {
        $turma = Turma::onlyTrashed()->findOrFail($id);
        $turma->restore();

        return redirect()->route('admin.dashboard', ['tab' => 'lixeira'])->with('success', "Turma {$turma->codigo} restaurada com sucesso!");
    }

    public function forceDeleteTurma(int $id): RedirectResponse
    {
        $turma = Turma::onlyTrashed()->findOrFail($id);
        $codigo = $turma->codigo;
        $turma->forceDelete();

        return redirect()->route('admin.dashboard', ['tab' => 'lixeira'])->with('success', "Turma {$codigo} excluida permanentemente.");
    }

    // ==================== PROFESSORES ====================

    public function storeProfessor(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'string', 'email', 'max:150', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'turmas' => ['nullable', 'array'],
            'turmas.*' => ['exists:turmas,id'],
        ], [
            'name.required' => 'O nome do professor e obrigatorio.',
            'email.unique' => 'Este e-mail ja esta cadastrado.',
            'password.min' => 'A senha deve ter no minimo 6 caracteres.',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'professor',
            'turma' => null,
            'turma_id' => null,
        ]);

        if (! empty($validated['turmas'])) {
            $user->turmasLecionadas()->sync($validated['turmas']);
        }

        return redirect()->route('admin.dashboard', ['tab' => 'professores'])->with('success', "Professor {$validated['name']} cadastrado com sucesso!");
    }

    public function updateProfessor(Request $request, User $user): RedirectResponse
    {
        if ($user->role !== 'professor') {
            abort(404);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'string', 'email', 'max:150', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:6'],
            'turmas' => ['nullable', 'array'],
            'turmas.*' => ['exists:turmas,id'],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        if (isset($validated['turmas'])) {
            $user->turmasLecionadas()->sync($validated['turmas']);
        }

        return redirect()->route('admin.dashboard', ['tab' => 'professores'])->with('success', "Professor {$user->name} atualizado com sucesso!");
    }

    public function destroyProfessor(User $user): RedirectResponse
    {
        if ($user->role !== 'professor') {
            abort(404);
        }

        $nome = $user->name;
        $user->delete();

        return redirect()->route('admin.dashboard', ['tab' => 'professores'])->with('success', "Professor {$nome} enviado para a lixeira.");
    }

    // ==================== ALUNOS ====================

    public function storeAluno(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'string', 'email', 'max:150', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'turma_id' => ['nullable', 'exists:turmas,id'],
        ], [
            'name.required' => 'O nome do aluno e obrigatorio.',
            'email.unique' => 'Este e-mail ja esta cadastrado.',
            'password.min' => 'A senha deve ter no minimo 6 caracteres.',
        ]);

        $turmaNome = null;
        if (! empty($validated['turma_id'])) {
            $t = Turma::find($validated['turma_id']);
            $turmaNome = $t?->label_completo;
        }

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'aluno',
            'turma_id' => $validated['turma_id'] ?? null,
            'turma' => $turmaNome,
        ]);

        return redirect()->route('admin.dashboard', ['tab' => 'alunos'])->with('success', "Aluno {$validated['name']} cadastrado com sucesso!");
    }

    public function updateAluno(Request $request, User $user): RedirectResponse
    {
        if ($user->role !== 'aluno') {
            abort(404);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'string', 'email', 'max:150', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:6'],
            'turma_id' => ['nullable', 'exists:turmas,id'],
        ]);

        $turmaNome = null;
        if (! empty($validated['turma_id'])) {
            $t = Turma::find($validated['turma_id']);
            $turmaNome = $t?->label_completo;
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->turma_id = $validated['turma_id'] ?? null;
        $user->turma = $turmaNome;

        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('admin.dashboard', ['tab' => 'alunos'])->with('success', "Aluno {$user->name} atualizado com sucesso!");
    }

    public function destroyAluno(User $user): RedirectResponse
    {
        if ($user->role !== 'aluno') {
            abort(404);
        }

        $nome = $user->name;
        $user->delete();

        return redirect()->route('admin.dashboard', ['tab' => 'alunos'])->with('success', "Aluno {$nome} enviado para a lixeira.");
    }

    // ==================== USUARIOS LIXEIRA ====================

    public function restoreUsuario(int $id): RedirectResponse
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();

        return redirect()->route('admin.dashboard', ['tab' => 'lixeira'])->with('success', "{$user->name} restaurado(a) com sucesso!");
    }

    public function forceDeleteUsuario(int $id): RedirectResponse
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $nome = $user->name;
        $user->forceDelete();

        return redirect()->route('admin.dashboard', ['tab' => 'lixeira'])->with('success', "{$nome} excluido(a) permanentemente.");
    }

    // ==================== COMUNICADOS ====================

    public function storeComunicado(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'titulo' => ['required', 'string', 'max:255'],
            'descricao' => ['required', 'string'],
            'categoria' => ['required', Rule::in(['Academico', 'Eventos', 'Avisos', 'Turmas', 'Urgente'])],
            'turma_id' => ['nullable', 'exists:turmas,id'],
            'importancia' => ['required', Rule::in(['normal', 'importante', 'urgente'])],
            'data_evento' => ['nullable', 'date'],
        ], [
            'titulo.required' => 'O titulo do comunicado e obrigatorio.',
            'descricao.required' => 'A descricao do comunicado e obrigatoria.',
            'categoria.required' => 'Selecione uma categoria valida.',
        ]);

        if ($validated['categoria'] === 'Urgente') {
            $validated['importancia'] = 'urgente';
        }

        $turmaStr = null;
        if (! empty($validated['turma_id'])) {
            $t = Turma::find($validated['turma_id']);
            $turmaStr = $t?->label_completo;
        }

        Comunicado::create([
            ...$validated,
            'turma' => $turmaStr,
            'autor_id' => Auth::id(),
        ]);

        return redirect()->route('admin.dashboard', ['tab' => 'comunicados'])->with('success', 'Comunicado publicado com sucesso!');
    }

    public function updateComunicado(Request $request, Comunicado $comunicado): RedirectResponse
    {
        $validated = $request->validate([
            'titulo' => ['required', 'string', 'max:255'],
            'descricao' => ['required', 'string'],
            'categoria' => ['required', Rule::in(['Academico', 'Eventos', 'Avisos', 'Turmas', 'Urgente'])],
            'turma_id' => ['nullable', 'exists:turmas,id'],
            'importancia' => ['required', Rule::in(['normal', 'importante', 'urgente'])],
            'data_evento' => ['nullable', 'date'],
        ]);

        if ($validated['categoria'] === 'Urgente') {
            $validated['importancia'] = 'urgente';
        }

        $turmaStr = null;
        if (! empty($validated['turma_id'])) {
            $t = Turma::find($validated['turma_id']);
            $turmaStr = $t?->label_completo;
        }

        $comunicado->update([...$validated, 'turma' => $turmaStr]);

        return redirect()->route('admin.dashboard', ['tab' => 'comunicados'])->with('success', 'Comunicado atualizado com sucesso!');
    }

    public function destroyComunicado(Comunicado $comunicado): RedirectResponse
    {
        $comunicado->delete();

        return redirect()->route('admin.dashboard', ['tab' => 'comunicados'])->with('success', 'Comunicado enviado para a lixeira.');
    }

    public function restoreComunicado(int $id): RedirectResponse
    {
        $comunicado = Comunicado::onlyTrashed()->findOrFail($id);
        $comunicado->restore();

        return redirect()->route('admin.dashboard', ['tab' => 'lixeira'])->with('success', 'Comunicado restaurado com sucesso!');
    }

    public function forceDeleteComunicado(int $id): RedirectResponse
    {
        $comunicado = Comunicado::onlyTrashed()->findOrFail($id);
        $comunicado->forceDelete();

        return redirect()->route('admin.dashboard', ['tab' => 'lixeira'])->with('success', 'Comunicado excluido permanentemente.');
    }

    // ==================== EVENTOS ====================

    public function storeEvento(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'titulo' => ['required', 'string', 'max:255'],
            'descricao' => ['required', 'string'],
            'data' => ['required', 'date'],
            'horario' => ['required', 'date_format:H:i'],
            'local' => ['required', 'string', 'max:150'],
        ]);

        Evento::create([...$validated, 'autor_id' => Auth::id()]);

        return redirect()->route('admin.dashboard', ['tab' => 'eventos'])->with('success', 'Evento institucional cadastrado com sucesso!');
    }

    public function destroyEvento(Evento $evento): RedirectResponse
    {
        $evento->delete();

        return redirect()->route('admin.dashboard', ['tab' => 'eventos'])->with('success', 'Evento enviado para a lixeira.');
    }

    public function restoreEvento(int $id): RedirectResponse
    {
        $evento = Evento::onlyTrashed()->findOrFail($id);
        $evento->restore();

        return redirect()->route('admin.dashboard', ['tab' => 'lixeira'])->with('success', 'Evento restaurado com sucesso!');
    }

    public function forceDeleteEvento(int $id): RedirectResponse
    {
        $evento = Evento::onlyTrashed()->findOrFail($id);
        $evento->forceDelete();

        return redirect()->route('admin.dashboard', ['tab' => 'lixeira'])->with('success', 'Evento excluido permanentemente.');
    }
}
