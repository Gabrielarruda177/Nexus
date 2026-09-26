<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Map each persona to its configuration, colors, and titles.
     *
     * @return array<string, array<string, string>>
     */
    public static function personaConfigs(): array
    {
        return [
            'administrador' => [
                'key' => 'administrador',
                'name' => 'Administrador',
                'title_login' => 'Login ADM',
                'subtitle' => 'Gestão de usuários, turmas, moderação de comunicados e monitoramento',
                'color' => '#0B1F3B',
                'color_gradient_start' => '#0B1F3B',
                'color_gradient_end' => '#1F464C',
                'color_name' => 'Azul Marinho / Gestão',
                'bg_light' => 'bg-slate-50',
                'border_color' => 'border-[#0B1F3B]',
                'text_color' => 'text-[#0B1F3B]',
                'btn_bg' => 'bg-[#0B1F3B]',
                'btn_hover' => 'hover:bg-[#1F464C]',
                'badge_bg' => 'bg-[#0B1F3B]/10 text-[#0B1F3B]',
                'icon' => 'shield-check',
                'demo_email' => 'admin@fatec.sp.gov.br',
                'demo_password' => 'admin123',
            ],
            'professor' => [
                'key' => 'professor',
                'name' => 'Professor',
                'title_login' => 'Login Docente',
                'subtitle' => 'Publicação de comunicados acadêmicos e organização de eventos',
                'color' => '#19707F',
                'color_gradient_start' => '#19707F',
                'color_gradient_end' => '#0097b2',
                'color_name' => 'Teal / Docência',
                'bg_light' => 'bg-teal-50',
                'border_color' => 'border-[#19707F]',
                'text_color' => 'text-[#19707F]',
                'btn_bg' => 'bg-[#19707F]',
                'btn_hover' => 'hover:bg-[#0097b2]',
                'badge_bg' => 'bg-[#19707F]/15 text-[#19707F]',
                'icon' => 'academic-cap',
                'demo_email' => 'professor@fatec.sp.gov.br',
                'demo_password' => 'prof123',
            ],
            'aluno' => [
                'key' => 'aluno',
                'name' => 'Aluno',
                'title_login' => 'Login Estudante',
                'subtitle' => 'Consulta centralizada de comunicados, turmas, prazos e eventos',
                'color' => '#0097b2',
                'color_gradient_start' => '#0097b2',
                'color_gradient_end' => '#1F3033',
                'color_name' => 'Nexus Teal / Estudante',
                'bg_light' => 'bg-cyan-50',
                'border_color' => 'border-[#0097b2]',
                'text_color' => 'text-[#0097b2]',
                'btn_bg' => 'bg-[#0097b2]',
                'btn_hover' => 'hover:bg-[#19707F]',
                'badge_bg' => 'bg-[#0097b2]/10 text-[#0097b2]',
                'icon' => 'sparkles',
                'demo_email' => 'aluno@fatec.sp.gov.br',
                'demo_password' => 'aluno123',
            ],
        ];
    }

    /**
     * Show profile selector landing page (/).
     */
    public function showSelector(): View|RedirectResponse
    {
        if (Auth::check()) {
            return match (Auth::user()->role) {
                'administrador' => redirect()->route('admin.dashboard'),
                'professor' => redirect()->route('professor.dashboard'),
                default => redirect()->route('comunicados.index'),
            };
        }

        $personas = self::personaConfigs();

        return view('selector', compact('personas'));
    }

    /**
     * Show login form tailored for specific persona.
     */
    public function showLogin(string $persona): View|RedirectResponse
    {
        $configs = self::personaConfigs();

        if (! array_key_exists($persona, $configs)) {
            return redirect()->route('home');
        }

        if (Auth::check()) {
            return match (Auth::user()->role) {
                'administrador' => redirect()->route('admin.dashboard'),
                'professor' => redirect()->route('professor.dashboard'),
                default => redirect()->route('comunicados.index'),
            };
        }

        $config = $configs[$persona];

        return view('auth.login', [
            'persona' => $persona,
            'config' => $config,
        ]);
    }

    /**
     * Handle login authentication.
     */
    public function login(Request $request, string $persona): RedirectResponse
    {
        $configs = self::personaConfigs();

        if (! array_key_exists($persona, $configs)) {
            return redirect()->route('home');
        }

        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'O campo e-mail é obrigatório.',
            'email.email' => 'Informe um endereço de e-mail válido.',
            'password.required' => 'O campo senha é obrigatório.',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();

            // Check if user's role matches persona
            if ($user->role !== $persona) {
                $targetRoute = match ($user->role) {
                    'administrador' => 'admin.dashboard',
                    'professor' => 'professor.dashboard',
                    default => 'comunicados.index',
                };

                return redirect()->route($targetRoute)->with(
                    'info',
                    "Você foi autenticado com sucesso como {$user->role_label}."
                );
            }

            return match ($user->role) {
                'administrador' => redirect()->route('admin.dashboard')->with('success', 'Bem-vindo ao Painel do Administrador!'),
                'professor' => redirect()->route('professor.dashboard')->with('success', 'Bem-vindo ao Painel do Professor!'),
                default => redirect()->route('comunicados.index')->with('success', 'Bem-vindo ao Nexus FATEC!'),
            };
        }

        return back()
            ->withInput($request->only('email', 'remember'))
            ->withErrors([
                'email' => 'As credenciais fornecidas não conferem com nossos registros.',
            ]);
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Sessão encerrada com sucesso.');
    }
}
