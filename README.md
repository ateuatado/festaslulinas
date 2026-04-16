# 🎉 Festas Lulinas — Sistema de Gestão

Plataforma web para **registro e divulgação de Festas Lulinas** em todo o Brasil. Organizadores cadastram eventos, fazem upload de fotos/vídeos para aprovação e solicitam materiais de apoio via loja integrada.

---

## 📋 Stack Tecnológica

| Camada | Tecnologia |
|--------|-----------|
| Framework | **CodeIgniter 4** (PHP 8.2) |
| Autenticação | **CodeIgniter Shield** |
| Banco de Dados | **MariaDB 10.4** (produção) / SQLite3 in-memory (testes) |
| Servidor local | **XAMPP** |
| Testes | **PHPUnit 10.x** |

---

## 🗂 Estrutura do Projeto

```
lulinas/
├── app/
│   ├── Controllers/
│   │   ├── Admin.php          # Painel administrativo (mod. mídias, festas, produtos, apoiadores)
│   │   ├── Dashboard.php      # Área do organizador (CRUD de festas)
│   │   ├── Festa.php          # Página pública de uma festa
│   │   ├── Galeria.php        # Upload e gestão de mídias da festa
│   │   ├── Home.php           # Página inicial pública
│   │   └── Loja.php           # Loja de materiais vinculada a festas
│   ├── Models/
│   │   ├── ApoiadorModel.php  # Apoiadores/figuras públicas exibidos na home
│   │   ├── FestaModel.php     # Festas com soft delete e validação
│   │   ├── MidiaModel.php     # Fotos/vídeos enviados pelos organizadores
│   │   ├── PedidoModel.php    # Pedidos de material feitos na loja
│   │   ├── PedidoItemModel.php# Itens de cada pedido (pivot)
│   │   └── ProdutoModel.php   # Produtos disponíveis na loja
│   ├── Config/
│   │   ├── Routes.php         # Todas as rotas da aplicação
│   │   ├── Auth.php           # Configurações do Shield
│   │   └── Database.php       # Conexões (default + tests SQLite)
│   ├── Database/
│   │   ├── Migrations/        # 8 migrações incrementais
│   │   └── Seeds/             # Seeders de produção
│   └── Views/
│       ├── admin/             # Views do painel admin
│       ├── dashboard/         # Views do painel do organizador
│       ├── galeria/           # View de upload de mídias
│       ├── loja/              # View da loja de materiais
│       ├── layouts/           # Layout principal (Bootstrap)
│       ├── partials/          # Menu admin, nav, etc.
│       ├── home.php           # Página inicial
│       └── festa_publica.php  # Página pública de uma festa
├── public/
│   └── uploads/
│       ├── galeria/           # Mídias das festas (fotos/vídeos)
│       ├── produtos/          # Imagens dos produtos da loja
│       └── apoiadores/        # Fotos dos apoiadores
├── sql/
│   └── lulinas.sql            # Dump completo do banco (MariaDB)
├── tests/
│   ├── unit/
│   │   ├── HealthTest.php             # Teste padrão CI4
│   │   └── ModelConsistencyTest.php   # ★ 20 casos — Models sem banco
│   ├── feature/
│   │   ├── HomeTest.php               # ★ Página pública
│   │   ├── DashboardTest.php          # ★ CRUD de festas + auth
│   │   ├── GaleriaTest.php            # ★ Upload + BUG 2 regression
│   │   ├── LojaTest.php               # ★ Pedidos + BUG 4 regression
│   │   ├── AdminTest.php              # ★ Admin + BUG 2 regressions
│   │   └── DatabaseIntegrityTest.php  # ★ Transações + BUG 1 regression
│   └── _support/
│       └── Database/Seeds/
│           └── LulinasSeeder.php      # Dados para testes (SQLite)
└── phpunit.xml.dist                   # Configuração do PHPUnit
```

---

## 🚀 Instalação Local (XAMPP)

### 1. Pré-requisitos
- XAMPP com PHP 8.2+ e MariaDB 10.4+
- Composer

### 2. Clonar e instalar dependências
```bash
# Clonar na pasta do XAMPP
git clone <repo-url> c:\xampp\htdocs\lulinas
cd c:\xampp\htdocs\lulinas
composer install
```

### 3. Configurar variáveis de ambiente
```bash
# Copiar o arquivo de exemplo
cp env .env
```

Editar o `.env`:
```ini
CI_ENVIRONMENT = development

app.baseURL = 'http://localhost/lulinas/public/'

database.default.hostname = localhost
database.default.database = lulinas
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi
```

### 4. Criar o banco de dados
```sql
CREATE DATABASE lulinas CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
```

Importar o dump:
```bash
mysql -u root lulinas < sql/lulinas.sql
```

Ou rodar as migrações:
```bash
php spark migrate
```

### 5. Permissões de upload
```bash
# Linux/Mac
chmod -R 775 public/uploads writable/
```
No Windows, verificar que a pasta `public/uploads/` existe e o XAMPP tem permissão de escrita.

---

## 🗄 Modelo de Dados

```
users ──────┬── festas ─────┬── midias
(Shield)    │               └── pedidos ── pedidos_itens ── produtos
            └── auth_identities
                auth_groups_users
                auth_logins

apoiadores (independente — exibidos na home)
```

### Tabelas principais

| Tabela | Soft Delete | Timestamps | Descrição |
|--------|-------------|-----------|-----------|
| `festas` | ✅ | ✅ | Eventos cadastrados pelos organizadores |
| `midias` | ❌ | ✅ | Fotos/vídeos (pendente→aprovado/rejeitado) |
| `produtos` | ✅ | ✅ | Itens da loja de materiais |
| `pedidos` | ❌ | ✅ | Solicitações de material (status: Solicitado) |
| `pedidos_itens` | ❌ | ❌ | Pivot pedido ↔ produto |
| `apoiadores` | ✅ | ✅ | Figuras públicas na home (ordenados por prioridade) |

---

## 🔐 Autenticação e Autorização

- **Autenticação**: gerenciada pelo **CodeIgniter Shield** (e-mail + senha, Remember Me)
- **Sessão**: filtro `session` protege grupos de rotas (`dashboard`, `galeria`, `loja`, `admin`)
- **Admin**: verificado por `auth()->id() == 1` no `checkAdmin()` — usuário com `id=1` no banco

> **Nota:** O controle de admin por ID fixo é funcional mas frágil. Para escalar, migrar para grupos do Shield (`auth_groups_users`).

---

## 🛣 Rotas Principais

| Método | URL | Controller | Auth |
|--------|-----|-----------|------|
| GET | `/` | `Home::index` | Pública |
| GET | `/festa/:id` | `Festa::ver` | Pública |
| GET | `/loja` | `Loja::index` | Pública |
| GET/POST | `/login` `/logout` | Shield | — |
| GET | `/dashboard` | `Dashboard::index` | ✅ session |
| POST | `/dashboard/salvar` | `Dashboard::salvar` | ✅ session |
| GET | `/dashboard/editar/:id` | `Dashboard::editar` | ✅ session |
| POST | `/dashboard/atualizar/:id` | `Dashboard::atualizar` | ✅ session |
| GET | `/dashboard/excluir/:id` | `Dashboard::excluir` | ✅ session |
| GET | `/galeria/:id` | `Galeria::index` | ✅ session |
| POST | `/galeria/upload/:id` | `Galeria::upload` | ✅ session |
| **POST** | `/galeria/delete/:id` | `Galeria::delete` | ✅ session |
| GET | `/loja/:id` | `Loja::index` | ✅ session |
| POST | `/loja/salvar/:id` | `Loja::salvar` | ✅ session |
| GET | `/admin/midias` | `Admin::midias` | ✅ session + id=1 |
| GET | `/admin/midia/:id/:status` | `Admin::statusMidia` | ✅ session + id=1 |
| **POST** | `/admin/excluirFesta/:id` | `Admin::excluirFesta` | ✅ session + id=1 |
| **POST** | `/admin/excluirProduto/:id` | `Admin::excluirProduto` | ✅ session + id=1 |
| **POST** | `/admin/apoiadores/delete/:id` | `Admin::deletarApoiador` | ✅ session + id=1 |

> **Negrito** = rotas que eram GET e foram corrigidas para POST (segurança).

---

## 🧪 Testes Automatizados

### Executar todos os testes
```bash
cd c:\xampp\htdocs\lulinas
php vendor/bin/phpunit
```

### Executar por suíte
```bash
# Apenas unitários (sem banco, instantâneos)
php vendor/bin/phpunit --filter "Unit"

# Apenas feature (com SQLite in-memory)
php vendor/bin/phpunit --filter "Feature"

# Com relatório de cobertura (requer Xdebug)
php vendor/bin/phpunit --coverage-html build/logs/html
```

### Estrutura dos Testes

| Arquivo | Tipo | Casos | O que testa |
|---------|------|-------|-------------|
| `HealthTest` | Unit | 2 | Configuração básica do CI4 |
| `ModelConsistencyTest` | Unit | 20 | `allowedFields`, `validationRules`, soft deletes de todos os Models |
| `HomeTest` | Feature | 3 | Página pública — acesso sem auth |
| `DashboardTest` | Feature | 7 | CRUD de festas, auth, ownership, soft delete |
| `GaleriaTest` | Feature | 6 | Upload, acesso, BUG 2 regression (delete via GET removido) |
| `LojaTest` | Feature | 7 | Pedidos, loja pública, BUG 4 regression (rota duplicada) |
| `AdminTest` | Feature | 10 | Painel admin, moderação, BUG 2 regressions (3 rotas) |
| `DatabaseIntegrityTest` | Feature | 7 | Soft deletes, transações, BUG 1 regression (campo `preco`) |
| **Total** | | **~62** | |

> Os testes usam **SQLite3 in-memory** — nenhum banco externo necessário. O CI4 migra automaticamente antes de cada suíte.

---

## 🐛 Histórico de Bugs Corrigidos

| # | Bug | Arquivo | Correção |
|---|-----|---------|---------|
| 1 | Campo `preco` ausente em `ProdutoModel::allowedFields` | `ProdutoModel.php` | Adicionado `'preco'` ao array |
| 2 | Ações de delete via HTTP GET (perigo de CSRF/bots) | `Routes.php` + 4 views | Convertido para `POST` + `csrf_field()` |
| 3 | Validação de upload declarada mas nunca aplicada | `Galeria.php` | Validação real por MIME + tamanho |
| 4 | Rota `POST /loja/salvar` duplicada sem autenticação | `Routes.php` | Rota removida |

---

## 📂 Variáveis de Ambiente Relevantes

```ini
# Ambiente (development | production | testing)
CI_ENVIRONMENT = development

# URL base (sempre com barra final)
app.baseURL = 'http://localhost/lulinas/public/'

# Banco principal
database.default.hostname = localhost
database.default.database = lulinas
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi

# Banco de testes (configurado automaticamente pelo CI4 quando ENVIRONMENT=testing)
# Usa SQLite3 in-memory por padrão — não precisa configurar
```

---

## 📸 Upload de Arquivos

| Tipo | Pasta | Validação |
|------|-------|-----------|
| Fotos/vídeos de festas | `public/uploads/galeria/` | MIME: jpeg, png, mp4 · Max: 10MB |
| Imagens de produtos | `public/uploads/produtos/` | is_image · Max: 10MB |
| Fotos de apoiadores | `public/uploads/apoiadores/` | is_image · Max: 10MB |

---

## 🔄 Fluxo de Moderação de Mídias

```
1. Organizador faz upload → status: "pendente"
2. Admin acessa /admin/midias → lista pendentes
3. Admin aprova ou rejeita → status: "aprovado" ou "rejeitado"
4. Página pública /festa/:id exibe APENAS mídias "aprovadas"
```

---

## 🤝 Como Contribuir

1. Fork do repositório
2. Crie uma branch: `git checkout -b feature/minha-funcionalidade`
3. Rode os testes: `php vendor/bin/phpunit`
4. Commit e Pull Request

---

*Projeto desenvolvido em CodeIgniter 4 — Festas Lulinas 2026*
