# 🎓 Nexus FATEC — Central Unificada de Comunicação Estudantil

> **Hackathon FATEC Itaquera 2026**  
> **Tema:** *"Como a tecnologia pode melhorar a comunicação do estudante na FATEC Itaquera?"*  
> **Nova Tese do Projeto:**  
> *"O problema não é apenas a informação estar espalhada — é ela estar desordenada, sem filtro de turma e soterrada pelo ruído."*

---

## 💡 1. Pitch & Evolução da Narrativa do Problema

### O que mudou no problema para a apresentação?
No início do projeto, a dor foi formulada como:
> *"O problema não é a falta de informação, mas a informação estar espalhada."*

Durante o desenvolvimento e análise do fluxo de alunos, professores e coordenação, o diagnóstico evoluiu para algo **muito mais profundo e convincente para os jurados**:

1. **A informação não está apenas espalhada, ela está descontextualizada:**  
   Em grupos de WhatsApp e servidores de Discord, comunicados para turmas específicas chegam misturados para quem não tem aquela disciplina, gerando desinteresse generalizado.
2. **Ausência total de hierarquia de prioridades:**  
   Um aviso urgente de *"Alteração imediata de sala de prova integrada"* compete visualmente com memes, figurinhas e dúvidas pontuais em grupos de mensagem instantânea.
3. **Prazos e eventos oficiais invisíveis:**  
   Eventos acadêmicos, palestras e visitas técnicas externas (como a visita técnica do dia 25/10 ao Google) ficam soterrados no feed de rolagem e são esquecidos.
4. **Fadiga de comunicação:**  
   O aluno precisa checar 4 ou 5 canais distintos e, mesmo assim, corre o risco de perder uma data limite crucial.

---

### 📊 Estrutura Recomendada para o Slide do Problema (Antes vs. Depois)

Para impactar os jurados visualmente no slide de apresentação:

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

**Frase de impacto para falar no pitch:**  
> *"Hoje, um aviso urgente de troca de sala na FATEC tem o mesmo peso visual de uma figurinha de WhatsApp. O Nexus FATEC transforma esse caos de canais em um feed inteligente, onde o estudante tem foco no que é da sua turma, urgência visual nos alertas e controle de todos os seus eventos num só lugar."*

---

## 📌 2. Sobre o Projeto & Perfis de Acesso

O **Nexus FATEC** é uma plataforma web criada com foco na experiência do estudante da FATEC Itaquera, provendo três portas de entrada com papéis e visões sob medida:

| Persona | Permissões | Tela de Entrada | Destino Pós-Login |
|---|---|---|---|
| **Administrador** | Gestão de turmas, atribuição de professores e alunos, moderação geral de comunicados e eventos | `/login/administrador` | `/admin` (Painel Administrativo com abas) |
| **Professor** | Criação de comunicados direcionados a turmas específicas ou gerais, organização e publicação de eventos | `/login/professor` | `/professor` (Painel do Professor) |
| **Aluno** | Feed dinâmico estilo rede social, filtros por turma/categoria, avisos urgentes e calendário completo de eventos | `/login/aluno` | `/comunicados` (Feed Direto de Consulta) |

---

## 🎨 3. Identidade Visual & Design System

A aplicação foi estilizada seguindo rigorosamente os padrões de modernidade visual:

### Paleta de Cores Oficial (Tons Degradês de Azul e Teal)
- **Cor Principal**: `#0097b2` (Teal vibrante — Ações, destaques, botões e tabs ativas)
- **Degradê Tom 1**: `#19707F` (Teal profundo — Badges de eventos e cabeçalhos)
- **Degradê Tom 2**: `#1F464C` (Tonalidade intermediária para contraste)
- **Degradê Tom 3**: `#1F3033` (Superfície de profundidade)
- **Tom Noturno / Base**: `#293133` e `#0B1F3B` (Background de containers, gradientes e navbar)
- **Alerta Urgente**: `#D64545` *(reservado exclusivamente para avisos emergenciais e comunicados críticos)*

### Telas Inspiradas nos Exemplos de Design

1. **Tela Inicial Seletora (`/`) — Imersiva (Sem Navbar)**:
   - **Sem Navbar superior**: layout limpo e 100% imersivo em tela cheia;
   - **Background Oficial**: foto aérea real da FATEC Itaquera (`/img/fatec-bg.jpg`) com camada gradiente escura e efeito de pulso radial (`backgroundPulse`) para alta legibilidade;
   - Lado esquerdo com tipografia marcante, subtítulo em caixa alta com divisor inferior e **botões em glassmorphism** com bordas translúcidas, efeito de brilho passante no hover e indicador deslizante `→`;
   - Lado direito com **Logotipo Oficial FN** (`/img/logo-fn.png`) em cartão flutuante suave (*floatAnimation*).

2. **Telas de Autenticação (`/login/{persona}`) — Split 50/50 (Sem Navbar)**:
   - **Sem Navbar superior**: tela de login limpa e focada no formulário de autenticação;
   - Painel esquerdo com curvatura ergonômica (`border-radius: 0 100px 100px 0`), degradê com as cores da persona e **Logotipo Oficial FN** em destaque com efeito de elevação;
   - Painel direito com card branco elevado, **Logotipo Oficial FN** no topo, inputs com ícones informativos, botão de alternar visualização de senha (olho mágico), atalho de **Preenchimento Demo com 1 clique** e botão pílula moderno com transição suave;
   - Botão discreto para retornar à seleção de perfil.

---

## 🏛️ 4. Cursos Oficiais da FATEC Itaquera & Segmentação por Turmas

A plataforma foi mapeada com a grade oficial de cursos da **FATEC Itaquera**:

| Curso Oficial | Períodos Oficiais | Semestres Cadastrados no Seeder | Cenário da Apresentação Prática |
|---|---|---|---|
| **Desenvolvimento de Software Multiplataforma** | Tarde | 2º ao 6º Semestre (`DSM-2T` a `DSM-6T`) | **1º Semestre Tarde (`DSM-1T`) será criado ao vivo pelo Admin na demo!** |
| **Automação Industrial** | Tarde • Noite | 1º ao 6º Semestre (`AUT-1T` a `AUT-6N`) | Cadastrado no Seeder |
| **Fabricação Mecânica** | Noite | 1º ao 6º Semestre (`FM-1N` a `FM-6N`) | Cadastrado no Seeder |
| **Manutenção Industrial** | Manhã | 1º ao 6º Semestre (`MI-1M` a `MI-6M`) | Cadastrado no Seeder |
| **Mecânica - Processos de Soldagem** | Noite | 1º ao 6º Semestre (`SOL-1N` a `SOL-6N`) | Cadastrado no Seeder |
| **Refrigeração, Ventilação e Ar Condicionado** | Manhã • Noite | 1º ao 6º Semestre (`RAC-1M` a `RAC-6N`) | Cadastrado no Seeder |

### Roteiro Prático de Criação pelo Admin (Pitch Demo):
1. **Admin cria Turma ao vivo**: no painel (`/admin`), aba *Turmas*, clica em `+ Nova Turma`. Seleciona *Desenvolvimento de Software Multiplataforma*, *1º Semestre*, *Tarde*. O código `DSM-1T` é sugerido e gerado automaticamente.
2. **Admin cadastra Aluno ao vivo**: na aba *Alunos*, clica em `+ Cadastrar Aluno`, informa os dados e matricula na recém-criada turma `DSM-1T`.
3. **Admin cadastra Professor**: na aba *Professores*, pode criar um novo docente e vinculá-lo a turmas instantaneamente.

### Persistência Relacional em Banco de Dados (MySQL):
- Tabela `turmas`: 47 turmas pré-cadastradas nos 6 cursos oficiais.
- Tabela `users`: Usuários com papéis (`administrador`, `professor`, `aluno`) e chave estrangeira `turma_id`.
- Tabela `turma_professor`: Vínculo bidirecional entre corpo docente e turmas.
- Tabela `comunicados`: Avisos com atributos de importância (`normal`, `importante`, `urgente`), categoria e chave `turma_id`.
- Tabela `eventos`: Agenda acadêmica com data, horário, local e autor.

---

## 📅 5. Módulo de Eventos & Calendário Interativo

- **Exibição Integral de Todos os Eventos**:
  - Tanto em `/eventos` quanto na barra lateral de `/comunicados`, **todos os eventos cadastrados são apresentados**, sem corte artificial por datas próximas.
  - Evento de destaque confirmado no banco de dados para **25 de Outubro** (*"Ida a Google"*), com badge de destaque e detalhes de horário e ponto de encontro.
- **Widget de Calendário Mensal Dinâmico**:
  - Navegador mensal intuitivo (Janeiro a Dezembro de 2026);
  - Indicadores circulares (*dots*) nos dias com compromissos marcados;
  - Ao clicar em uma data, o card exibe instantaneamente o resumo dos eventos correspondentes.

---

## 📱 6. Experiência do Estudante: Feed Social & Visualização em Modal

Ao fazer login como Aluno (`/login/aluno`), o estudante é direcionado diretamente para `/comunicados`:
- **Visualização em Modal (Sem trocar de aba)**: ao clicar em qualquer post do feed ou da tabela do admin, o comunicado completo abre em um **modal interativo in-place**, preservando a posição de rolagem e sem abrir abas extras.
- **Card de Destaque Urgente no topo**: comunicados com prioridade máxima aparecem em caixa vermelha destacada para evitar desencontros.
- **Timeline de Postagens Estilo Rede Social**: cartões dinâmicos com foto/avatar do autor, tag da turma de destino, categoria colorida e data amigável.
- **Filtros por Turma e Categoria**: abas rápidas para alternar entre Minha Turma, Todos, Urgentes, Acadêmico, Eventos e Avisos.
- **Barra de Pesquisa Instantânea**: `/busca` por termos, matérias, turmas ou autores.

---

## 🗺️ 7. Mapa Completo de Rotas

| Método | Rota | Descrição | Acesso |
|---|---|---|---|
| `GET` | `/` | Seletor de perfis visual | Público |
| `GET` | `/login/{persona}` | Formulário de autenticação customizado | Público |
| `POST` | `/login/{persona}` | Processamento de login | Público |
| `POST` | `/logout` | Encerramento seguro da sessão | Autenticado |
| `GET` | `/comunicados` | Feed social de comunicados dos alunos | Aluno / Todos |
| `GET` | `/comunicados/{id}` | Visualização detalhada do comunicado | Aluno / Todos |
| `GET` | `/eventos` | Agenda completa com calendário interativo | Aluno / Todos |
| `GET` | `/busca` | Busca por termos, professores, turmas | Aluno / Todos |
| `GET` | `/admin` | Painel de controle do Administrador | Administrador |
| `POST` | `/admin/turmas` | Criação e gerenciamento de turmas | Administrador |
| `POST` | `/admin/professores` | Cadastro de professores | Administrador |
| `POST` | `/admin/alunos` | Cadastro e enturmação de alunos | Administrador |
| `POST` | `/admin/comunicados` | Publicação direta pela coordenação | Administrador |
| `GET` | `/professor` | Painel do Professor com postagens e eventos | Professor |
| `POST` | `/professor/comunicados` | Criação de comunicados segmentados por turma | Professor |
| `POST` | `/professor/eventos` | Criação de eventos acadêmicos | Professor |

---

## 🔑 8. Credenciais de Demonstração (Prontas no Seeder)

| Perfil | E-mail | Senha | Função / Dados |
|---|---|---|---|
| **Administrador** | `admin@fatec.sp.gov.br` | `admin123` | Coordenação Geral do Campus |
| **Professor** | `professor@fatec.sp.gov.br` | `prof123` | Prof. Carlos Eduardo Silveira |
| **Aluno** | `aluno@fatec.sp.gov.br` | `aluno123` | Gabriel Santos Alencar (DSM - 3º Noturno) |

> 💡 **Agilidade no Pitch:** As telas de login possuem botão de **"Preencher Demo"** com 1 clique para agilizar a demonstração diante dos jurados.

---

## ⚡ 9. Instalação e Execução Local

```bash
# 1. Instalar dependências
composer install
npm install

# 2. Configurar ambiente (.env com MySQL configurado)
cp .env.example .env
php artisan key:generate

# 3. Executar migrações e popular dados prévios
php artisan migrate --seed

# 4. Compilar assets do frontend
npm run build

# 5. Iniciar o servidor de aplicação
php artisan serve
```

Acesse no navegador: **`http://localhost:8000`**

---

## 🧪 10. Testes Automatizados & Qualidade de Código

A aplicação possui cobertura automatizada de testes de integração via PHPUnit, validando:
- Exibição da home e formulários de autenticação por persona;
- Fluxo de ponta a ponta (login admin -> cadastro professor -> comunicado urgente -> consulta aluno);
- Proteção de rotas por papel (`role`);
- Criação e gerenciamento de Turmas;
- Segmentação de Comunicados por Turma;
- Renderização de todos os eventos e do evento de **25 de Outubro** no calendário.

Para rodar os testes:
```bash
php artisan test
```

Para formatar o código com Laravel Pint:
```bash
vendor/bin/pint --format agent
```

---

*Desenvolvido com excelência para o Hackathon FATEC Itaquera 2026.*
#
