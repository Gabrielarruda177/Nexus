<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! Auth::check()) {
            $firstRole = $roles[0] ?? 'aluno';

            return redirect()->route('login.form', ['persona' => $firstRole])
                ->with('error', 'Por favor, realize login para acessar esta página.');
        }

        $user = Auth::user();

        if (! in_array($user->role, $roles, true)) {
            // Redirect user to their own area if they don't have access
            $targetRoute = match ($user->role) {
                'administrador' => 'admin.dashboard',
                'professor' => 'professor.dashboard',
                'aluno' => 'comunicados.index',
                default => 'home',
            };

            return redirect()->route($targetRoute)
                ->with('error', 'Acesso restrito ao seu perfil de usuário.');
        }

        return $next($request);
    }
}
