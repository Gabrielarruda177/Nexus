# 🎓 Nexus FATEC — Central Unificada de Comunicação Estudantil

> **Hackathon FATEC Itaquera 2026**
> **Tema:** *"Como a tecnologia pode melhorar a comunicação do estudante na FATEC Itaquera?"*
> **Nova tese do projeto:** *"O problema não é apenas a informação estar espalhada — é ela estar desordenada, sem filtro de turma e soterrada pelo ruído."*

Plataforma web que substitui a comunicação dispersa em WhatsApp, Discord, Instagram e mural físico por **um canal oficial central**, com segmentação por turma, hierarquia de urgência e calendário acadêmico.

---

## 📋 Sumário

1. [O Problema](#1-o-problema)
2. [A Solução](#2-a-solução)
3. [Stack Tecnológica](#3-stack-tecnológica)
4. [Perfis de Acesso](#4-perfis-de-acesso)
5. [Identidade Visual](#5-identidade-visual)
6. [Cursos e Segmentação por Turmas](#6-cursos-e-segmentação-por-turmas)
7. [Módulos e Funcionalidades](#7-módulos-e-funcionalidades)
8. [Roteiro da Demonstração](#8-roteiro-da-demonstração)
9. [Credenciais de Demonstração](#9-credenciais-de-demonstração)
10. [Instalação e Execução Local](#10-instalação-e-execução-local)
11. [Estrutura do Projeto](#11-estrutura-do-projeto)
12. [Mapa Completo de Rotas](#12-mapa-completo-de-rotas)
13. [Testes e Qualidade de Código](#13-testes-e-qualidade-de-código)

---

## 1. O Problema

No início do projeto a dor foi formulada como: *"O problema não é a falta de informação, mas a informação estar espalhada."*

Durante o desenvolvimento e a análise do fluxo de alunos, professores e coordenação, o diagnóstico evoluiu para algo **mais profundo e mais convincente para o júri**:

1. **A informação não está apenas espalhada, ela está descontextualizada.**
   Em grupos de WhatsApp e servidores de Discord, comunicados destinados a turmas específicas chegam misturados para quem não cursa aquela disciplina, gerando desinteresse generalizado.

2. **Ausência total de hierarquia de prioridades.**
   Um aviso urgente de *"alteração imediata de sala de prova integrada"* compete visualmente com memes, figurinhas e dúvidas pontuais.

3. **Prazos e eventos oficiais invisíveis.**
   Eventos acadêmicos, palestras e visitas técnicas externas (como a visita técnica do dia 25/10 ao Google) ficam soterrados no feed de rolagem e são esquecidos.

4. **Fadiga de comunicação.**
   O aluno precisa checar 4 ou 5 canais distintos e, mesmo assim, corre o risco de perder uma data limite crucial.

### Roteiro do slide "Antes vs. Depois"

```
┌────────────────────────────────────────────────────────┐
│  SLIDE: O DIAGNÓSTICO DO ESTUDANTE NA FATEC ITAQUERA   │
├──────────────────────────┬─────────────────────────────┤
│ ❌ ANTES: O CAOS ATUAL   │  ✅ DEPOIS: COM O NEXUS     │
├──────────────────────────┼─────────────────────────────┤
│ • 5+ canais desconectados│ • Canal Oficial Central     │
│   (WhatsApp, Discord,    │   com acesso por perfil     │
│    Instagram, Mural)     │                             │
│ • Aviso urgente perdido  │ • Alerta Urgente em         │
│   em 300 mensagens       │   Destaque Vermelho no topo │
│ • Falta de direcionamento│ • Filtro Direto por Turma   │
│   (aluno recebe o que    │   (o aluno vê o que é seu)  │
│    não é dele)           │                             │
│ • Eventos e datas        │ • Calendário Interativo     │
│   esquecidos no chat     │   com todos os eventos      │
└──────────────────────────┴─────────────────────────────┘
```

> 💡 **Frase de impacto para o pitch:**
> *"Hoje, um aviso urgente de troca de sala na FATEC tem o mesmo peso visual de uma figurinha de WhatsApp. O Nexus FATEC transforma esse caos de canais em um feed inteligente, onde o estudante tem foco no que é da sua turma, urgência visual nos alertas e controle de todos os seus eventos num só lugar."*

---

## 2. A Solução

O **Nexus FATEC** é uma plataforma web criada com foco na experiência do estudante, provendo três portas de entrada com papéis e visões sob medida:

| Antes (caos) | Depois (Nexus) |
|---|---|
| 5+ canais desconectados | Canal oficial central com acesso por perfil |
| Aviso urgente perdido em 300 mensagens | Alerta urgente em destaque vermelho no topo do feed |
| Aluno recebe o que não é dele | Filtro direto por turma e categoria |
| Eventos e datas esquecidos no chat | Calendário interativo com todos os eventos do ano |

**Destaques técnicos que sustentam a proposta:**

- Autorização por papel (`role`) aplicada no próprio grupo de rotas via middleware
- Segmentação de comunicados por `turma_id`, com filtragem no feed do aluno
- Nível de importância (`normal` · `importante` · `urgente`) controlado pelo autor
- Lixeira (soft delete) com restauração e exclusão definitiva em todas as entidades
- Seeders com as turmas e cursos reais da FATEC Itaquera

---

## 3. Stack Tecnológica

| Camada | Tecnologia | Versão |
|---|---|---|
| Linguagem | PHP | `^8.3` |
| Framework | Laravel | `^13.17` |
| Frontend | Blade + Tailwind CSS | `^4.0` |
| Build | Vite | `^8.0` |
| Banco de dados | SQLite (padrão) / MySQL 8 | — |
| Testes | PHPUnit | `^12.5` |
| Qualidade de código | Laravel Pint | `^1.27` |
| Dev server | Laravel Boost + Pail | `^2.10` · `^1.2` |

---

## 4. Perfis de Acesso

| Persona | Permissões | Tela de Entrada | Destino Pós-Login |
|---|---|---|---|
| **Administrador** | Gestão de turmas, cadastro e enturmação de professores e alunos, moderação de comunicados e eventos, acesso à lixeira | `/login/administrador` | `/admin` (Painel Administrativo com abas) |
| **Professor** | Criação de comunicados direcionados a turmas específicas ou gerais, organização e publicação de eventos | `/login/professor` | `/professor` (Painel do Professor) |
| **Aluno** | Feed dinâmico estilo rede social, filtros por turma/categoria, avisos urgentes e calendário completo de eventos | `/login/aluno` | `/comunicados` (Feed Direto de Consulta) |

> ⚠️ **Acesso por perfil:** cada persona tem uma rota de login própria (`/login/{persona}`) e é redirecionada para um painel diferente conforme o `role` do usuário. Rotas fora do perfil são bloqueadas e redirecionadas.

---

## 5. Identidade Visual

### Paleta de Cores Oficial (tons degradês de azul e teal)

| Uso | Cor |
|---|---|
| Cor principal (ações, destaques, botões e tabs ativas) | `#0097b2` |
| Degradê tom 1 (badges de eventos e cabeçalhos) | `#19707F` |
| Degradê tom 2 (tonalidade intermediária de contraste) | `#1F464C` |
| Degradê tom 3 (superfície de profundidade) | `#1F3033` |
| Tom noturno / base (backgrounds, gradientes e navbar) | `#293133` · `#0B1F3B` |
| Alerta urgente (uso exclusivo) | `#D64545` |

### Telas

**1. Tela Inicial Seletora (`/`) — imersiva, sem navbar**

- Layout limpo em tela cheia, 100% imersivo
- Foto aérea real da FATEC Itaquera (`/img/fatec-bg.jpg`) com camada gradiente escura e efeito de pulso radial (`backgroundPulse`) para alta legibilidade
- Lado esquerdo: tipografia marcante, subtítulo em caixa alta com divisor inferior e **botões em glassmorphism** com bordas translúcidas, brilho passante no hover e indicador `→`
- Lado direito: **Logotipo Oficial FN** (`/img/logo-fn.png`) em cartão flutuante suave (*floatAnimation*)

**2. Telas de Autenticação (`/login/{persona}`) — split 50/50, sem navbar**

- Painel esquerdo com curvatura ergonômica (`border-radius: 0 100px 100px 0`), degradê nas cores da persona e logotipo em destaque com efeito de elevação
- Painel direito com card branco elevado, logotipo no topo, inputs com ícones, botão de mostrar/ocultar senha, atalho de **preenchimento demo com 1 clique** e botão pílula com transição suave
- Botão discreto para retornar à seleção de perfil

---

## 6. Cursos e Segmentação por Turmas

A plataforma foi mapeada com a grade oficial de cursos da **FATEC Itaquera**:

| Curso Oficial | Períodos | Semestres no Seeder | Observação |
|---|---|---|---|
| **Desenvolvimento de Software Multiplataforma** | Tarde | 2º ao 6º (`DSM-2T` a `DSM-6T`) | `DSM-1T` **não** é criado no seeder de propósito: nasce ao vivo na demo |
| **Automação Industrial** | Tarde · Noite | 1º ao 6º (`AUT-1T` a `AUT-6N`) | Cadastrado no seeder |
| **Fabricação Mecânica** | Noite | 1º ao 6º (`FM-1N` a `FM-6N`) | Cadastrado no seeder |
| **Manutenção Industrial** | Manhã | 1º ao 6º (`MI-1M` a `MI-6M`) | Cadastrado no seeder |
| **Mecânica — Processos de Soldagem** | Noite | 1º ao 6º (`SOL-1N` a `SOL-6N`) | Cadastrado no seeder |
| **Refrigeração, Ventilação e Ar Condicionado** | Manhã · Noite | 1º ao 6º (`RAC-1M` a `RAC-6N`) | Cadastrado no seeder |

**Total: 47 turmas** pré-cadastradas distribuídas nos 6 cursos oficiais.

### Modelo de dados

| Tabela | Responsabilidade |
|---|---|
| `turmas` | Turmas com curso, semestre, período e código (`DSM-3T`) |
| `users` | Usuários com `role` (`administrador`, `professor`, `aluno`) e FK `turma_id` |
| `turma_professor` | Tabela pivot do vínculo many-to-many entre docente e turmas |
| `comunicados` | Avisos com `importancia` (`normal`, `importante`, `urgente`), categoria, `autor_id` e `turma_id` |
| `eventos` | Agenda acadêmica com data, horário, local e `autor_id` |

Todas as cinco entidades usam **soft delete** (`deleted_at`), o que dá origem à lixeira do painel administrativo.

> ⚠️ **Bug conhecido — soft delete não está migrado.** A migration `2026_09_26_201307_add_soft_deletes_to_all_tables.php` foi criada mas nunca implementada: é o stub padrão do `make:migration`, referenciando uma tabela `all_tables` inexistente e sem adicionar coluna alguma. Como `Schema::table()` com blueprint vazio não executa SQL, a migration passa como sucesso e mente — a coluna `deleted_at` não é criada em nenhum banco.
>
> Os 4 models já usam o trait `SoftDeletes`, então a lixeira só funciona onde as colunas foram adicionadas manualmente. Em um banco novo, o sintoma é `SQLSTATE[HY000]: no such column: turmas.deleted_at` e a suíte de testes falha em 7 de 9 casos.
>
> **Correção pendente:** implementar a migration com `$table->softDeletes()` para `turmas`, `users`, `comunicados` e `eventos` (idealmente com guarda `Schema::hasColumn`, caso as colunas tenham sido criadas à mão no MySQL), e então rodar `php artisan migrate:fresh --seed`.

---

## 7. Módulos e Funcionalidades

### 📅 Eventos e Calendário Interativo

- **Exibição integral**: tanto em `/eventos` quanto na barra lateral de `/comunicados`, **todos** os eventos cadastrados são apresentados, sem corte artificial por datas próximas
- Evento de destaque no banco: **25 de Outubro** — *"Ida a Google"*, com badge de destaque, horário e ponto de encontro
- **Widget de calendário mensal dinâmico**: navegação de Janeiro a Dezembro, indicadores circulares (*dots*) nos dias com compromissos e resumo instantâneo ao clicar numa data

### 📱 Experiência do Estudante

Ao fazer login como aluno, o estudante vai direto para `/comunicados`:

- **Visualização em modal**: clicar em qualquer post do feed abre o comunicado completo **in-place**, preservando a posição de rolagem e sem abrir abas extras
- **Card de destaque urgente no topo**: comunicados de prioridade máxima aparecem em caixa vermelha destacada
- **Timeline estilo rede social**: cards com foto/avatar do autor, tag da turma de destino, categoria colorida e data amigável
- **Filtros por turma e categoria**: abas rápidas para Minha Turma, Todos, Urgentes, Acadêmico, Eventos e Avisos
- **Busca instantânea** em `/busca` por termos, matérias, turmas ou autores

### 🗑️ Lixeira (Soft Delete)

Todos os registros apagados vão para a aba **Lixeira** do painel administrativo, nunca somem de imediato:

- **Restaurar**: devolve o registro ao estado ativo, com a listagem reordenada por data de remoção
- **Excluir definitivamente**: `forceDelete` permanente, para quando o erro de exclusão for irreversível por decisão da coordenação
- Contador de itens na lixeira exibido no topo do painel
- Disponível para: **comunicados, eventos, turmas, professores e alunos**

### 🛡️ Controle de Acesso

- Middleware `EnsureRole` com parâmetros de perfil (`role:administrador`, `role:professor`, `role:aluno,professor,administrador`)
- Sessão encerrada com `POST /logout`
- Perfis sem permissão são redirecionados para o painel correspondente ao seu próprio `role`

---

## 8. Roteiro da Demonstração

Sequência sugerida para o pitch diante do júri:

| Passo | Persona | Ação | Tela |
|---|---|---|---|
| 1 | Público | Abre a home e mostra o caos diagnosticado | `/` |
| 2 | Administrador | Faz login com 1 clique ("Preencher Demo") | `/login/administrador` |
| 3 | Administrador | **Cria a turma `DSM-1T` ao vivo** (aba *Turmas* → `+ Nova Turma`) | `/admin?tab=turmas` |
| 4 | Administrador | Cadastra um aluno e matricula na turma recém-criada | `/admin?tab=alunos` |
| 5 | Administrador | Cadastra um professor e vincula-o a turmas | `/admin?tab=professores` |
| 6 | Administrador | Publica um comunicado **urgente** | `/admin?tab=comunicados` |
| 7 | Administrador | Apaga um registro e mostra a **lixeira**, depois restaura | `/admin?tab=lixeira` |
| 8 | Professor | Faz login e publica um comunicado segmentado por turma | `/professor` |
| 9 | Professor | Cria um evento no calendário | `/professor?tab=eventos` |
| 10 | Aluno | Faz login e vai direto ao feed: mostra o card urgente vermelho | `/comunicados` |
| 11 | Aluno | Abre um comunicado no **modal**, sem sair da página | `/comunicados` |
| 12 | Aluno | Filtra por *Minha Turma* e por categoria | `/comunicados` |
| 13 | Aluno | Busca por termo e abre a agenda com o evento de 25/10 | `/busca` · `/eventos` |

> 💡 **Agilidade no pitch:** as três telas de login têm botão de **"Preencher Demo"** com 1 clique. Use-o para não digitar credenciais na frente do júri.

---

## 9. Credenciais de Demonstração

Criadas e prontas pelo `DatabaseSeeder`:

| Perfil | E-mail | Senha | Persona |
|---|---|---|---|
| **Administrador** | `admin@fatec.sp.gov.br` | `admin123` | Coordenação Geral do Campus |
| **Professor** | `professor@fatec.sp.gov.br` | `prof123` | Prof. Carlos Eduardo Silveira (DSM-3T, DSM-2T, AUT-2T) |
| **Aluno** | `aluno@fatec.sp.gov.br` | `aluno123` | Gabriel Santos Alencar — DSM-3T (3º Semestre Tarde) |

**Corpo docente adicional** (senha `prof123`):

| E-mail | Nome | Turmas vinculadas |
|---|---|---|
| `mariana.costa@fatec.sp.gov.br` | Prof.ª Dra. Mariana Costa | FM-3N, MI-2M |
| `roberto.almeida@fatec.sp.gov.br` | Prof. Dr. Roberto Almeida | SOL-4N, RAC-2M |
| `fernanda.lima@fatec.sp.gov.br` | Prof.ª Fernanda Lima | AUT-2T, DSM-3T |

**Alunos adicionais** (senha `aluno123`): `beatriz.lima@` (AUT-2T), `lucas.oliveira@` (FM-3N), `juliana.mendes@` (MI-2M), `rodrigo.santos@` (SOL-4N), `camila.neves@` (RAC-2M), `matheus.araujo@` (DSM-2T) — todos com domínio `@fatec.sp.gov.br`.

> ⚠️ **Dados de demonstração apenas.** Todas as senhas acima são fixtures de pitch. Troque-as ou remova o seeder antes de qualquer uso real.

---

## 10. Instalação e Execução Local

### Opção rápida — script pronto

```bash
composer run setup
```

O script instala as dependências, cria o `.env`, gera a `APP_KEY`, roda as migrations e compila os assets. Depois é só subir o servidor:

```bash
php artisan serve
```

### Opção manual — passo a passo

```bash
# 1. Instalar dependências
composer install
npm install

# 2. Configurar o ambiente
cp .env.example .env
php artisan key:generate

# 3. Banco de dados: usar o SQLite já configurado no .env.example
#    (crie o arquivo do banco uma única vez)
touch database/database.sqlite

# 4. Migrar e popular com os dados da demo
php artisan migrate --seed

# 5. Compilar os assets do frontend
npm run build

# 6. Subir o servidor
php artisan serve
```

Acesse no navegador: **`http://localhost:8000`**

<details>
<summary>Usando MySQL em vez de SQLite</summary>

O `.env.example` já vem com `DB_CONNECTION=sqlite` para funcionar sem configuração. Para usar MySQL, ajuste o `.env`:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nexus_fatec
DB_USERNAME=root
DB_PASSWORD=
```

```bash
php artisan migrate:fresh --seed
```

</details>

> 💡 **Desenvolvimento com hot reload:** durante a apresentação, rode `npm run dev` em um terminal e `php artisan serve` em outro. Para acompanhar logs em tempo real, use `php artisan pail`.

---

## 11. Estrutura do Projeto

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php          # Seletor de perfil e login por persona
│   │   ├── ComunicadoController.php    # Feed, busca e calendário
│   │   ├── AdminController.php         # Painel admin: CRUD completo + lixeira
│   │   └── ProfessorController.php     # Painel do professor
│   └── Middleware/
│       └── EnsureRole.php              # Autorização por role
├── Models/
│   ├── User.php                        # Administrador, Professor e Aluno
│   ├── Turma.php
│   ├── Comunicado.php
│   └── Evento.php
resources/
├── css/app.css                         # Tailwind 4 + tokens de tema
├── js/app.js                           # Bootstrap bundle
└── views/
    ├── selector.blade.php              # Home imersiva
    ├── auth/login.blade.php            # Login por persona
    ├── admin/dashboard.blade.php       # Abas + lixeira
    ├── professor/dashboard.blade.php
    ├── comunicados/                    # index, show, busca
    ├── eventos/index.blade.php         # Calendário mensal
    └── layouts/app.blade.php
database/
├── migrations/                         # Inclui add_soft_deletes_to_all_tables
└── seeders/DatabaseSeeder.php          # 47 turmas, 4 professores, 7 alunos, eventos
routes/web.php
tests/Feature/NexusFatecTest.php
```

---

## 12. Mapa Completo de Rotas

**38 rotas** no total. As colunas de acesso refletem o middleware `role` de cada grupo.

### Públicas

| Método | Rota | Descrição | Acesso |
|---|---|---|---|
| `GET` | `/` | Seletor visual de perfis | Público |
| `GET` | `/login/{persona}` | Formulário de autenticação customizado | Público |
| `POST` | `/login/{persona}` | Processamento do login | Público |
| `POST` | `/logout` | Encerramento seguro da sessão | Autenticado |

### Consulta (aluno, professor e administrador)

| Método | Rota | Descrição | Acesso |
|---|---|---|---|
| `GET` | `/comunicados` | Feed social de comunicados | 3 perfis |
| `GET` | `/comunicados/{comunicado}` | Visualização detalhada do comunicado | 3 perfis |
| `GET` | `/eventos` | Agenda completa com calendário interativo | 3 perfis |
| `GET` | `/busca` | Busca por termos, professores e turmas | 3 perfis |

### Painel do administrador (`role:administrador`)

| Método | Rota | Descrição |
|---|---|---|
| `GET` | `/admin` | Painel de controle com abas e lixeira |
| `POST` | `/admin/turmas` | Criação de turmas |
| `PUT` | `/admin/turmas/{turma}` | Edição de turma |
| `DELETE` | `/admin/turmas/{turma}` | Envia turma para a lixeira |
| `POST` | `/admin/turmas/{id}/restore` | Restaura turma da lixeira |
| `DELETE` | `/admin/turmas/{id}/force` | Exclusão definitiva de turma |
| `POST` | `/admin/professores` | Cadastro de professor |
| `PUT` | `/admin/professores/{user}` | Edição de professor |
| `DELETE` | `/admin/professores/{user}` | Envia professor para a lixeira |
| `POST` | `/admin/alunos` | Cadastro e enturmação de aluno |
| `PUT` | `/admin/alunos/{user}` | Edição de aluno |
| `DELETE` | `/admin/alunos/{user}` | Envia aluno para a lixeira |
| `POST` | `/admin/usuarios/{id}/restore` | Restaura professor ou aluno da lixeira |
| `DELETE` | `/admin/usuarios/{id}/force` | Exclusão definitiva de professor ou aluno |
| `POST` | `/admin/comunicados` | Publicação direta pela coordenação |
| `PUT` | `/admin/comunicados/{comunicado}` | Edição de comunicado |
| `DELETE` | `/admin/comunicados/{comunicado}` | Envia comunicado para a lixeira |
| `POST` | `/admin/comunicados/{id}/restore` | Restaura comunicado da lixeira |
| `DELETE` | `/admin/comunicados/{id}/force` | Exclusão definitiva de comunicado |
| `POST` | `/admin/eventos` | Cadastro de evento |
| `DELETE` | `/admin/eventos/{evento}` | Envia evento para a lixeira |
| `POST` | `/admin/eventos/{id}/restore` | Restaura evento da lixeira |
| `DELETE` | `/admin/eventos/{id}/force` | Exclusão definitiva de evento |

### Painel do professor (`role:professor`)

| Método | Rota | Descrição |
|---|---|---|
| `GET` | `/professor` | Painel com postagens e eventos |
| `POST` | `/professor/comunicados` | Criação de comunicado segmentado por turma |
| `PUT` | `/professor/comunicados/{comunicado}` | Edição de comunicado |
| `DELETE` | `/professor/comunicados/{comunicado}` | Remove comunicado |
| `POST` | `/professor/eventos` | Criação de evento acadêmico |
| `PUT` | `/professor/eventos/{evento}` | Edição de evento |
| `DELETE` | `/professor/eventos/{evento}` | Remove evento |

> 💡 **Para inspecionar:** `php artisan route:list` mostra as 38 rotas com nome, middleware e controller.

---

## 13. Testes e Qualidade de Código

A aplicação possui cobertura automatizada de integração via PHPUnit em `tests/Feature/NexusFatecTest.php`, validando:

- Exibição da home e dos formulários de autenticação por persona
- Fluxo de ponta a ponta (login admin → cadastro de professor → comunicado urgente → consulta do aluno → busca)
- Proteção de rotas por papel, com redirecionamento do perfil não autorizado
- Criação e gerenciamento de turmas
- Segmentação de comunicados por turma
- Renderização de todos os eventos, incluindo o evento de 25 de outubro

```bash
# Rodar a suíte completa
php artisan test

# Rodar apenas o arquivo de feature do Nexus
php artisan test --filter=NexusFatecTest

# Formatar o código com o Pint
vendor/bin/pint --format agent
```

> ⚠️ **Estado atual da suíte:** 2 de 9 testes passam, 7 falham com `no such column: turmas.deleted_at`. A causa é a migration de soft delete não implementada (ver [Cursos e Segmentação por Turmas](#6-cursos-e-segmentação-por-turmas)), não os testes em si. A suíte deve ficar verde depois da correção.

> 💡 **Antes de entregar:** rode `vendor/bin/pint --format agent` e `php artisan test` para garantir que o commit final está formatado e com a suíte verde.

---

<<<<<<< HEAD
*Desenvolvido para o 1º Hackathon FATEC Itaquera 2026.*
=======
*Desenvolvido com excelência para o Hackathon FATEC Itaquera 2026.*
#
>>>>>>> d735202e106547df0eda53bfc9e3f5f8dad6c894
