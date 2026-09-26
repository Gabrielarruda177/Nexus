<?php

namespace Database\Seeders;

use App\Models\Comunicado;
use App\Models\Evento;
use App\Models\Turma;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * Cursos oficiais da FATEC Itaquera:
     * - Automação Industrial (Tarde • Noite)
     * - Desenvolvimento de Software Multiplataforma (Tarde) [1º Semestre NÃO criado para a demo ao vivo!]
     * - Fabricação Mecânica (Noite)
     * - Manutenção Industrial (Manhã)
     * - Mecânica - Processos de Soldagem (Noite)
     * - Refrigeração, Ventilação e Ar Condicionado (Manhã • Noite)
     */
    public function run(): void
    {
        // ==========================================
        // 1. TURMAS OFICIAIS DA FATEC ITAQUERA
        // ==========================================

        // 1.1 Automação Industrial - Tarde (1º ao 6º)
        for ($sem = 1; $sem <= 6; $sem++) {
            Turma::firstOrCreate(
                ['codigo' => "AUT-{$sem}T"],
                [
                    'nome' => "Automação Industrial - {$sem}º Tarde",
                    'curso' => 'Automação Industrial',
                    'semestre' => "{$sem}º Semestre",
                    'periodo' => 'Tarde',
                    'descricao' => "Turma do {$sem}º semestre do curso de Automação Industrial, período vespertino.",
                ]
            );
        }

        // 1.2 Automação Industrial - Noite (1º ao 6º)
        for ($sem = 1; $sem <= 6; $sem++) {
            Turma::firstOrCreate(
                ['codigo' => "AUT-{$sem}N"],
                [
                    'nome' => "Automação Industrial - {$sem}º Noite",
                    'curso' => 'Automação Industrial',
                    'semestre' => "{$sem}º Semestre",
                    'periodo' => 'Noturno',
                    'descricao' => "Turma do {$sem}º semestre do curso de Automação Industrial, período noturno.",
                ]
            );
        }

        // 1.3 Desenvolvimento de Software Multiplataforma - Tarde (2º ao 6º)
        // NOTA: O 1º Semestre Tarde (DSM-1T) NÃO é criado aqui para permitir a criação ao vivo pelo Admin na apresentação!
        for ($sem = 2; $sem <= 6; $sem++) {
            Turma::firstOrCreate(
                ['codigo' => "DSM-{$sem}T"],
                [
                    'nome' => "Desenvolvimento de Software Multiplataforma - {$sem}º Tarde",
                    'curso' => 'Desenvolvimento de Software Multiplataforma',
                    'semestre' => "{$sem}º Semestre",
                    'periodo' => 'Tarde',
                    'descricao' => "Turma avançada do {$sem}º semestre de Desenvolvimento de Software Multiplataforma.",
                ]
            );
        }

        // 1.4 Fabricação Mecânica - Noite (1º ao 6º)
        for ($sem = 1; $sem <= 6; $sem++) {
            Turma::firstOrCreate(
                ['codigo' => "FM-{$sem}N"],
                [
                    'nome' => "Fabricação Mecânica - {$sem}º Noite",
                    'curso' => 'Fabricação Mecânica',
                    'semestre' => "{$sem}º Semestre",
                    'periodo' => 'Noturno',
                    'descricao' => "Turma do {$sem}º semestre do curso de Fabricação Mecânica, período noturno.",
                ]
            );
        }

        // 1.5 Manutenção Industrial - Manhã (1º ao 6º)
        for ($sem = 1; $sem <= 6; $sem++) {
            Turma::firstOrCreate(
                ['codigo' => "MI-{$sem}M"],
                [
                    'nome' => "Manutenção Industrial - {$sem}º Manhã",
                    'curso' => 'Manutenção Industrial',
                    'semestre' => "{$sem}º Semestre",
                    'periodo' => 'Matutino',
                    'descricao' => "Turma do {$sem}º semestre do curso de Manutenção Industrial, período matutino.",
                ]
            );
        }

        // 1.6 Mecânica - Processos de Soldagem - Noite (1º ao 6º)
        for ($sem = 1; $sem <= 6; $sem++) {
            Turma::firstOrCreate(
                ['codigo' => "SOL-{$sem}N"],
                [
                    'nome' => "Mecânica (Soldagem) - {$sem}º Noite",
                    'curso' => 'Mecânica - Processos de Soldagem',
                    'semestre' => "{$sem}º Semestre",
                    'periodo' => 'Noturno',
                    'descricao' => "Turma do {$sem}º semestre do curso de Mecânica - Processos de Soldagem, período noturno.",
                ]
            );
        }

        // 1.7 Refrigeração, Ventilação e Ar Condicionado - Manhã (1º ao 6º)
        for ($sem = 1; $sem <= 6; $sem++) {
            Turma::firstOrCreate(
                ['codigo' => "RAC-{$sem}M"],
                [
                    'nome' => "Refrigeração e Ar Condicionado - {$sem}º Manhã",
                    'curso' => 'Refrigeração, Ventilação e Ar Condicionado',
                    'semestre' => "{$sem}º Semestre",
                    'periodo' => 'Matutino',
                    'descricao' => "Turma do {$sem}º semestre do curso de Refrigeração, Ventilação e Ar Condicionado matutino.",
                ]
            );
        }

        // 1.8 Refrigeração, Ventilação e Ar Condicionado - Noite (1º ao 6º)
        for ($sem = 1; $sem <= 6; $sem++) {
            Turma::firstOrCreate(
                ['codigo' => "RAC-{$sem}N"],
                [
                    'nome' => "Refrigeração e Ar Condicionado - {$sem}º Noite",
                    'curso' => 'Refrigeração, Ventilação e Ar Condicionado',
                    'semestre' => "{$sem}º Semestre",
                    'periodo' => 'Noturno',
                    'descricao' => "Turma do {$sem}º semestre do curso de Refrigeração, Ventilação e Ar Condicionado noturno.",
                ]
            );
        }

        // Referência das turmas chave para associação
        $turmaDsm3 = Turma::where('codigo', 'DSM-3T')->first();
        $turmaDsm2 = Turma::where('codigo', 'DSM-2T')->first();
        $turmaAut2 = Turma::where('codigo', 'AUT-2T')->first();
        $turmaFm3 = Turma::where('codigo', 'FM-3N')->first();
        $turmaMi2 = Turma::where('codigo', 'MI-2M')->first();
        $turmaSol4 = Turma::where('codigo', 'SOL-4N')->first();
        $turmaRac2 = Turma::where('codigo', 'RAC-2M')->first();

        // ==========================================
        // 2. ADMINISTRADOR PADRÃO (DEMO)
        // ==========================================
        $admin = User::firstOrCreate(
            ['email' => 'admin@fatec.sp.gov.br'],
            [
                'name' => 'Prof. Coordenação Geral',
                'password' => Hash::make('admin123'),
                'role' => 'administrador',
                'turma' => null,
                'turma_id' => null,
            ]
        );

        // ==========================================
        // 3. CORPO DOCENTE (PROFESSORES)
        // ==========================================
        $prof1 = User::firstOrCreate(
            ['email' => 'professor@fatec.sp.gov.br'],
            [
                'name' => 'Prof. Carlos Eduardo Silveira',
                'password' => Hash::make('prof123'),
                'role' => 'professor',
                'turma' => null,
                'turma_id' => null,
            ]
        );

        $prof2 = User::firstOrCreate(
            ['email' => 'mariana.costa@fatec.sp.gov.br'],
            [
                'name' => 'Profa. Dra. Mariana Costa',
                'password' => Hash::make('prof123'),
                'role' => 'professor',
                'turma' => null,
                'turma_id' => null,
            ]
        );

        $prof3 = User::firstOrCreate(
            ['email' => 'roberto.almeida@fatec.sp.gov.br'],
            [
                'name' => 'Prof. Dr. Roberto Almeida',
                'password' => Hash::make('prof123'),
                'role' => 'professor',
                'turma' => null,
                'turma_id' => null,
            ]
        );

        $prof4 = User::firstOrCreate(
            ['email' => 'fernanda.lima@fatec.sp.gov.br'],
            [
                'name' => 'Profa. Fernanda Lima',
                'password' => Hash::make('prof123'),
                'role' => 'professor',
                'turma' => null,
                'turma_id' => null,
            ]
        );

        // Vincular professores às turmas correspondentes
        if ($turmaDsm3 && $turmaDsm2 && $turmaAut2) {
            $prof1->turmasLecionadas()->syncWithoutDetaching([$turmaDsm3->id, $turmaDsm2->id, $turmaAut2->id]);
        }
        if ($turmaFm3 && $turmaMi2) {
            $prof2->turmasLecionadas()->syncWithoutDetaching([$turmaFm3->id, $turmaMi2->id]);
        }
        if ($turmaSol4 && $turmaRac2) {
            $prof3->turmasLecionadas()->syncWithoutDetaching([$turmaSol4->id, $turmaRac2->id]);
        }
        if ($turmaAut2 && $turmaDsm3) {
            $prof4->turmasLecionadas()->syncWithoutDetaching([$turmaAut2->id, $turmaDsm3->id]);
        }

        // ==========================================
        // 4. ALUNOS VINCULADOS ÀS TURMAS
        // ==========================================

        // Aluno Principal da Demo: Gabriel Santos Alencar em DSM - 3º Semestre Tarde
        $aluno1 = User::firstOrCreate(
            ['email' => 'aluno@fatec.sp.gov.br'],
            [
                'name' => 'Gabriel Santos Alencar',
                'password' => Hash::make('aluno123'),
                'role' => 'aluno',
                'turma' => $turmaDsm3?->label_completo ?? 'DSM-3T - Desenvolvimento de Software Multiplataforma (3º Semestre Tarde)',
                'turma_id' => $turmaDsm3?->id,
            ]
        );
        if ($turmaDsm3) {
            $aluno1->update([
                'turma_id' => $turmaDsm3->id,
                'turma' => $turmaDsm3->label_completo,
            ]);
        }

        // Alunos em outros cursos e semestres
        $alunosExtra = [
            [
                'name' => 'Beatriz Rocha Lima',
                'email' => 'beatriz.lima@fatec.sp.gov.br',
                'turma' => $turmaAut2,
            ],
            [
                'name' => 'Lucas Vinícius Oliveira',
                'email' => 'lucas.oliveira@fatec.sp.gov.br',
                'turma' => $turmaFm3,
            ],
            [
                'name' => 'Juliana Mendes Prado',
                'email' => 'juliana.mendes@fatec.sp.gov.br',
                'turma' => $turmaMi2,
            ],
            [
                'name' => 'Rodrigo Barbosa Santos',
                'email' => 'rodrigo.santos@fatec.sp.gov.br',
                'turma' => $turmaSol4,
            ],
            [
                'name' => 'Camila Ferreira Neves',
                'email' => 'camila.ferreira@fatec.sp.gov.br',
                'turma' => $turmaRac2,
            ],
            [
                'name' => 'Matheus Silva Araújo',
                'email' => 'matheus.araujo@fatec.sp.gov.br',
                'turma' => $turmaDsm2,
            ],
        ];

        foreach ($alunosExtra as $alData) {
            $u = User::firstOrCreate(
                ['email' => $alData['email']],
                [
                    'name' => $alData['name'],
                    'password' => Hash::make('aluno123'),
                    'role' => 'aluno',
                    'turma' => $alData['turma']?->label_completo,
                    'turma_id' => $alData['turma']?->id,
                ]
            );
            if ($alData['turma']) {
                $u->update([
                    'turma_id' => $alData['turma']->id,
                    'turma' => $alData['turma']->label_completo,
                ]);
            }
        }

        // ==========================================
        // 5. COMUNICADOS OFICIAIS COM SEGMENTAÇÃO
        // ==========================================
        if (Comunicado::count() === 0) {
            // Urgente para DSM-3T
            Comunicado::create([
                'titulo' => 'URGENTE: Alteração de Sala na Avaliação Prática DSM',
                'descricao' => "Atenção estudantes do 3º Semestre de Desenvolvimento de Software Multiplataforma (Tarde):\nDevido a manutenção emergencial nos roteadores do Bloco B, a Avaliação Prática de hoje ocorrerá no Laboratório 04 (Bloco Central).\nSolicitamos que todos cheguem com 15 minutos de antecedência.",
                'categoria' => 'Urgente',
                'turma' => $turmaDsm3?->label_completo,
                'turma_id' => $turmaDsm3?->id,
                'autor_id' => $prof1->id,
                'importancia' => 'urgente',
                'data_evento' => now()->toDateString(),
            ]);

            // Geral: Hackathon FATEC Itaquera 2026
            Comunicado::create([
                'titulo' => 'Inscrições Abertas: Hackathon FATEC Itaquera 2026',
                'descricao' => "Estão abertas as inscrições para o Hackathon FATEC Itaquera 2026!\nO tema deste ano é 'Como a tecnologia pode melhorar a comunicação do estudante na FATEC Itaquera'. Monte sua equipe com colegas de qualquer curso e concorra a mentorias com empresas de tecnologia e premiação especial para o melhor projeto.",
                'categoria' => 'Eventos',
                'turma' => null,
                'turma_id' => null,
                'autor_id' => $admin->id,
                'importancia' => 'importante',
                'data_evento' => now()->addDays(5)->toDateString(),
            ]);

            // Geral: Matrículas e SIGA
            Comunicado::create([
                'titulo' => 'Cronograma de Rematrícula Online via SIGA',
                'descricao' => "A Secretaria Acadêmica informa o calendário oficial de rematrícula para o próximo semestre letivo.\nFiquem atentos aos pré-requisitos das disciplinas de Estágio Supervisionado e Trabalho de Graduação (TG). O sistema estará aberto a partir das 08h da próxima segunda-feira.",
                'categoria' => 'Acadêmico',
                'turma' => null,
                'turma_id' => null,
                'autor_id' => $admin->id,
                'importancia' => 'importante',
                'data_evento' => now()->addDays(12)->toDateString(),
            ]);

            // Segmentado para Automação Industrial
            Comunicado::create([
                'titulo' => 'Visita Técnica aos Laboratórios de Robótica e CLP',
                'descricao' => 'Alunos de Automação Industrial: teremos uma demonstração técnica de novas bancadas didáticas industriais e integração com redes industriais nesta quinta-feira às 15h.',
                'categoria' => 'Turmas',
                'turma' => $turmaAut2?->label_completo,
                'turma_id' => $turmaAut2?->id,
                'autor_id' => $prof4->id,
                'importancia' => 'normal',
                'data_evento' => now()->addDays(8)->toDateString(),
            ]);

            // Geral: Biblioteca e Laboratórios
            Comunicado::create([
                'titulo' => 'Horário Estendido: Laboratórios de Informática e Biblioteca',
                'descricao' => 'Durante a semana de entregas e avaliações, a Biblioteca e os Laboratórios 01 e 02 funcionarão com horário estendido até às 22h45 para grupos de estudo da FATEC Itaquera.',
                'categoria' => 'Avisos',
                'turma' => null,
                'turma_id' => null,
                'autor_id' => $admin->id,
                'importancia' => 'normal',
                'data_evento' => null,
            ]);
        }

        // ==========================================
        // 6. EVENTOS (INCLUINDO 25 DE OUTUBRO)
        // ==========================================
        if (Evento::count() === 0) {
            Evento::create([
                'titulo' => 'Hackathon FATEC Itaquera 2026',
                'descricao' => 'Maratona presencial de inovação e desenvolvimento de soluções tecnológicas para os alunos da FATEC Itaquera.',
                'data' => now()->addDays(5)->toDateString(),
                'horario' => '08:30:00',
                'local' => 'Auditório Principal & Laboratórios 3 e 4',
                'autor_id' => $prof1->id,
            ]);

            // Evento solicitado no dia 25 de Outubro (Ida a Google)
            $currentYear = now()->year;
            Evento::create([
                'titulo' => 'Ida a Google',
                'descricao' => 'Todos se encontram na fatec, para imos todos junto. Visita técnica aos escritórios da Google na Paulista.',
                'data' => "{$currentYear}-10-25",
                'horario' => '10:00:00',
                'local' => 'Paulista',
                'autor_id' => $prof1->id,
            ]);

            Evento::create([
                'titulo' => 'Semana de Tecnologia e Inovação (SEMATEC)',
                'descricao' => 'Ciclo de palestras, workshops práticos e estandes de recrutamento com empresas parceiras de tecnologia e indústria.',
                'data' => now()->addDays(14)->toDateString(),
                'horario' => '19:00:00',
                'local' => 'Auditório Geral da FATEC Itaquera',
                'autor_id' => $admin->id,
            ]);

            Evento::create([
                'titulo' => 'Workshop: Indústria 4.0 e Automação Conectada',
                'descricao' => 'Treinamento prático sobre integração de sistemas ciberfísicos, sensoriamento e monitoramento em tempo real.',
                'data' => now()->addDays(20)->toDateString(),
                'horario' => '14:00:00',
                'local' => 'Laboratório de Manutenção e Automação',
                'autor_id' => $prof2->id,
            ]);
        }
    }
}
