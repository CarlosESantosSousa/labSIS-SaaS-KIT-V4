# Sistema de Roles e Permissions

## 📋 Índice

- [Introdução](#introdução)
- [Por que usar Roles e Permissões?](#por-que-usar-roles-e-permissões)
- [Pacote Utilizado (Spatie)](#pacote-utilizado-spatie)
- [Arquitetura no Projeto](#arquitetura-no-projeto)
- [Implementação](#implementação)
- [Gerenciamento no Filament](#gerenciamento-no-filament)
  - [PermissionRole (Permissões por Role)](#permissionrole-permissões-por-role)
  - [UserRole (Funções por Usuário)](#userrole-funções-por-usuário)
- [Boas Práticas](#boas-práticas)
- [Problemas Comuns](#problemas-comuns)
- [Conclusão](#conclusão)

## Introdução

Este documento descreve como o sistema de **roles** (papeis) e **permissions** (permissões) foi implementado neste projeto, integrando a experiência de UI/UX do Filament com o controle fino de autorização na aplicação.

## Por que usar Roles/Permissions?

Sistemas reais possuem diferentes perfis de usuários e responsabilidades. Sem um controle de acesso granular, riscos como ações indevidas, vazamento de informações e inconsistências de negócio tornam-se comuns.

Com roles e permissions você consegue:

- Garantir que somente usuários autorizados executem ações sensíveis;
- Segregar funções (ex.: Administrador vs Usuário comum);
- Evoluir a segurança de forma incremental sem reescrever a aplicação;
- Integrar facilmente com políticas (Policies) e middlewares de autorização.

## Pacote Utilizado (Spatie)

Utilizamos o pacote `spatie/laravel-permission`, referência no ecossistema Laravel para gerenciamento de roles e permissions.

- **Instalação e orientações oficiais**: consulte a documentação da Spatie para detalhes de instalação e configuração inicial: `https://spatie.be/docs/laravel-permission/v6/installation-laravel`.

Após a instalação, o pacote registra tabelas para `roles`, `permissions` e pivots que relacionam usuários a essas entidades. Ele também provê traits e APIs simples como `assignRole()`, `hasRole()` e `can()`.

## Arquitetura no Projeto

Os principais arquivos que centralizam a configuração e aplicação das permissões são:

- `app/Enums/RoleType.php`: enum que define as roles disponíveis no sistema (ex.: `Admin`, `User`).
- `app/Enums/Permission.php`: enum que define e padroniza todas as permissions do sistema (ex.: `create`, `view`, `update`, `delete`). Ele:
  - Evita strings “mágicas” e erros de digitação;
  - Centraliza a lista de permissions válidas;
  - Facilita o seeding inicial das permissions no banco.
- `app/Models/User.php`: modelo do usuário que utiliza o trait `Spatie\Permission\Traits\HasRoles` e contém a lógica de acesso ao painel do Filament.
- Clusters do Filament para administração:
  - `app/Filament/Clusters/PermissionRole/`: cluster para gerenciar permissões por role.
  - `app/Filament/Clusters/UserRole/`: cluster para gerenciar as roles atribuídas a cada usuário.

Motivos para usar um `enum` (`RoleType`):

- Evita strings “soltas” no código, reduzindo erros de digitação;
- Facilita a integração entre backend e UI (rótulos e valores consistentes);
- Centraliza os papéis válidos do sistema, simplificando manutenção e evolução.

Estratégia de permissões no projeto:

- As permissions são semeadas a partir de `app/Enums/Permission.php` no `UserSeeder` (persistidas com `firstOrCreate`).
- No cadastro inicial, nenhuma permission é atribuída automaticamente a usuários ou roles.
- As permissions serão atribuídas a roles (ex.: `RoleType::ADMIN`, `RoleType::USER`) usando APIs do pacote (ex.: `givePermissionTo()`), e os usuários herdarão essas permissions ao receberem a role (ex.: `assignRole()`).
- O usuário Admin acessa o painel `admin` por role; o usuário comum inicia sem permissions, recebendo-as futuramente via roles conforme a necessidade.

## Implementação

### 1. Enum de Roles

O enum `RoleType` lista e padroniza os papéis disponíveis, além de fornecer rótulos para UI.

**Importante**: Para este projeto não será disponibilizada uma interface na UI para a criação de novas roles, pois entendemos que cada projeto é único e possui requisitos diferentes, portanto as roles devem ser definidas de forma programática para garantir consistência e controle total sobre a estrutura de autorização. A proposta deste projeto é que as roles devem ser criadas por meio da enum `RoleType` e o seeder `User`, permitindo que desenvolvedores adaptem o sistema de permissões conforme as necessidades específicas de cada aplicação.

Arquivo: `app/Enums/RoleType.php`

### 2. Modelo de Usuário

O modelo `User` adota o trait `HasRoles` e implementa o controle de acesso ao painel administrativo (Filament) através do método `canAccessPanel()`:

- Garante e-mail verificado;
- Bloqueia usuários suspensos;
- Restringe o painel `admin` somente a quem possui a role `RoleType::ADMIN`.

Arquivo: `app/Models/User.php`

### 3. Seeds e Factory

- `database/seeders/UserSeeder.php`: cria as roles (`Admin`, `User`) e assegura um usuário administrador e um usuário padrão, ambos com senha `mudar123`.
- `database/factories/UserFactory.php`: fornece states (`admin()` e `user()`) para criar usuários já com suas respectivas roles.

### 4. Filament Panel

O painel administrativo está em `app/Providers/Filament/AdminPanelProvider.php` e possui `id('admin')`. A verificação em `User::canAccessPanel()` usa este `id` para garantir que apenas administradores acessem o painel.

## Gerenciamento no Filament

A administração é feita por dois clusters: `PermissionRole` (permissões por role) e `UserRole` (roles por usuário). Ambos ficam no painel `admin`, grupo de navegação “Configurações”.

### PermissionRole (Permissões por Role)

- Caminho: `Admin > Configurações > Permissões`.
- Páginas disponíveis (exemplos): `Mídia`, `Usuários`.
- Em cada página há uma tabela onde cada linha representa uma role (exceto Admin, conforme regra do projeto) e as colunas representam as ações (permissões) padrão: `Visualizar`, `Criar`, `Editar`, `Apagar`.
- Cada célula é um toggle. Ao habilitar:
  - A permission correspondente é criada caso não exista (`findOrCreate`) e atribuída à role.
  - Ao desabilitar, a permission é removida da role.
- Implementação base: `app/Filament/Clusters/PermissionRole/Pages/BasePermissionsPage.php`.

### UserRole (Funções por Usuário)

- Caminho: `Admin > Configurações > Funções > Atribuir Funções`.
- Página: `app/Filament/Clusters/UserRole/Pages/AssignRoles.php`.
- Estrutura em tabela com 3 colunas fixas:
  - `Nome` (usuário);
  - `Admin` (toggle);
  - `User` (toggle).
- Regras de UX/negócio aplicadas:
  - Exclusividade entre roles: ao habilitar `Admin`, a role `User` é automaticamente removida, e vice-versa.
    - Implementado com `syncRoles([RoleType::ADMIN])` ou `syncRoles([RoleType::USER])`, refletindo imediatamente na UI.
  - Linha do próprio usuário autenticado não é exibida para evitar auto-atribuição.
    - Implementado na query: `->when(Filament::auth()->check(), fn ($query) => $query->whereKeyNot(Filament::auth()->id()))`.
  - Guard utilizado: `web`.

Dica: No infolist do usuário, exibimos um badge com a função atual em `app/Filament/Resources/Users/Schemas/UserInfolist.php`.

## Boas Práticas

- Padronize as roles e permissions via `Enum` para evitar strings soltas.
- Prefira atribuir permissions às roles; usuários herdam permissions via roles.
- Revise periodicamente as roles (ex.: `User` deve possuir o mínimo necessário; `Admin` pode ter todas as permissions).
- Para mudanças estruturais (novas permissions), ajuste o seeder e rode migrações/seed conforme necessário.

## Problemas Comuns

- Permissões não aplicam após mudança: limpe cache de config/rotas/views (`php artisan optimize:clear`).
- Roles/permissions faltando: confirme se as migrations do Spatie foram executadas e os seeders rodaram.
- Uso de UUID: se a PK do seu modelo for UUID, ajuste as migrations/config conforme a documentação avançada da Spatie.

## Conclusão

Com `spatie/laravel-permission` a aplicação ganha um controle de acesso robusto, flexível e idiomático no Laravel, integrado ao Filament para uma ótima experiência administrativa. Os clusters `PermissionRole` e `UserRole` oferecem uma UI clara para manutenção contínua de permissões e papéis.


