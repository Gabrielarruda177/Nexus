<?php

namespace Tests\Feature;

use App\Models\Comunicado;
use App\Models\Turma;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NexusFatecTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    /**
     * Test 1: Home page loads profile selector correctly.
     */
    public function test_home_page_displays_profile_selector(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Seja bem-vindo ao Nexus FATEC');
        $response->assertSee('Administrador');
        $response->assertSee('Professor');
        $response->assertSee('Aluno');
    }

    /**
     * Test 2: Login pages render per persona with correct context.
     */
    public function test_login_pages_render_per_persona(): void
    {
        $adminLogin = $this->get('/login/administrador');
        $adminLogin->assertStatus(200);
        $adminLogin->assertSee('Administrador');

        $profLogin = $this->get('/login/professor');
        $profLogin->assertStatus(200);
        $profLogin->assertSee('Professor');

        $alunoLogin = $this->get('/login/aluno');
        $alunoLogin->assertStatus(200);
        $alunoLogin->assertSee('Aluno');
    }

    /**
     * Test 3: Complete demonstration flow from Section 9.
     */
    public function test_full_demo_flow(): void
    {
        // 1. Admin login & redirection to /admin
        $admin = User::where('role', 'administrador')->first();
        $this->assertNotNull($admin);

        $loginResponse = $this->post('/login/administrador', [
            'email' => $admin->email,
            'password' => 'admin123',
        ]);
        $loginResponse->assertRedirect(route('admin.dashboard'));

        // 2. Admin registers a new professor
        $createProfResponse = $this->actingAs($admin)->post('/admin/professores', [
            'name' => 'Prof. Novo Teste',
            'email' => 'novo.prof@fatec.sp.gov.br',
            'password' => 'prof123',
        ]);
        $createProfResponse->assertRedirect(route('admin.dashboard', ['tab' => 'professores']));
        $this->assertDatabaseHas('users', ['email' => 'novo.prof@fatec.sp.gov.br', 'role' => 'professor']);

        // 3. Admin creates an Urgent comunicado
        $createComResponse = $this->actingAs($admin)->post('/admin/comunicados', [
            'titulo' => 'Aviso Urgente: Teste de Fluxo',
            'descricao' => 'Descrição do comunicado urgente para validação da demonstração.',
            'categoria' => 'Urgente',
            'importancia' => 'urgente',
        ]);
        $createComResponse->assertRedirect(route('admin.dashboard', ['tab' => 'comunicados']));
        $this->assertDatabaseHas('comunicados', ['titulo' => 'Aviso Urgente: Teste de Fluxo']);

        // 4. Aluno logs in & goes straight to /comunicados
        $aluno = User::where('role', 'aluno')->first();
        $this->assertNotNull($aluno);

        $alunoLoginResponse = $this->post('/login/aluno', [
            'email' => $aluno->email,
            'password' => 'aluno123',
        ]);
        $alunoLoginResponse->assertRedirect(route('comunicados.index'));

        // 5. Aluno views /comunicados feed and sees the urgent notice
        $feedResponse = $this->actingAs($aluno)->get('/comunicados');
        $feedResponse->assertStatus(200);
        $feedResponse->assertSee('Aviso Urgente: Teste de Fluxo');

        // 6. Aluno views the comunicado details
        $comunicado = Comunicado::where('titulo', 'Aviso Urgente: Teste de Fluxo')->first();
        $detailResponse = $this->actingAs($aluno)->get("/comunicados/{$comunicado->id}");
        $detailResponse->assertStatus(200);
        $detailResponse->assertSee('Aviso Urgente: Teste de Fluxo');
        $detailResponse->assertSee('Descrição do comunicado urgente para validação da demonstração.');

        // 7. Aluno searches for the comunicado
        $searchResponse = $this->actingAs($aluno)->get('/busca?q=Aviso+Urgente');
        $searchResponse->assertStatus(200);
        $searchResponse->assertSee('Aviso Urgente: Teste de Fluxo');
    }

    /**
     * Test 4: Role-based authorization & protection.
     */
    public function test_role_security_restricts_unauthorized_access(): void
    {
        $aluno = User::where('role', 'aluno')->first();

        // Aluno cannot access admin dashboard
        $response = $this->actingAs($aluno)->get('/admin');
        $response->assertRedirect(route('comunicados.index'));

        // Aluno cannot access professor dashboard
        $response = $this->actingAs($aluno)->get('/professor');
        $response->assertRedirect(route('comunicados.index'));

        // Professor cannot access admin dashboard
        $prof = User::where('role', 'professor')->first();
        $response = $this->actingAs($prof)->get('/admin');
        $response->assertRedirect(route('professor.dashboard'));
    }

    /**
     * Test 5: Admin can create and manage Turmas.
     */
    public function test_admin_can_create_turma(): void
    {
        $admin = User::where('role', 'administrador')->first();

        $response = $this->actingAs($admin)->post('/admin/turmas', [
            'nome' => 'Desenvolvimento de Software Multiplataforma - 4º Noturno',
            'codigo' => 'DSM4-N',
            'curso' => 'Desenvolvimento de Software Multiplataforma',
            'semestre' => '4º Semestre',
            'periodo' => 'Noturno',
            'descricao' => 'Turma criada via teste de integração.',
        ]);

        $response->assertRedirect(route('admin.dashboard', ['tab' => 'turmas']));
        $this->assertDatabaseHas('turmas', [
            'codigo' => 'DSM4-N',
            'periodo' => 'Noturno',
        ]);
    }

    /**
     * Test 6: Professor can create Comunicado targeted to a specific Turma.
     */
    public function test_professor_can_create_comunicado_for_specific_turma(): void
    {
        $prof = User::where('role', 'professor')->first();
        $turma = Turma::first();

        $response = $this->actingAs($prof)->post('/professor/comunicados', [
            'titulo' => 'Entrega de Projeto Prático Semestral',
            'descricao' => 'Instruções para entrega do MVP no repositório.',
            'categoria' => 'Acadêmico',
            'turma_id' => $turma->id,
            'importancia' => 'importante',
        ]);

        $response->assertRedirect(route('professor.dashboard', ['tab' => 'comunicados']));
        $this->assertDatabaseHas('comunicados', [
            'titulo' => 'Entrega de Projeto Prático Semestral',
            'turma_id' => $turma->id,
            'autor_id' => $prof->id,
        ]);
    }

    /**
     * Test 7: /eventos and /comunicados display all events including October 25 event.
     */
    public function test_eventos_page_renders_all_events_including_october_event(): void
    {
        $aluno = User::where('role', 'aluno')->first();

        $eventosResponse = $this->actingAs($aluno)->get('/eventos');
        $eventosResponse->assertStatus(200);
        $eventosResponse->assertSee('Calendário FATEC');
        $eventosResponse->assertSee('Ida a Google');
        $eventosResponse->assertSee('Destaque 25/10');

        $feedResponse = $this->actingAs($aluno)->get('/comunicados');
        $feedResponse->assertStatus(200);
        $feedResponse->assertSee('Ida a Google');
    }
}
