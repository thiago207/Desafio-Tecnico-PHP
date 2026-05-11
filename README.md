# AgendaPRO

Sistema de agenda eletronica desenvolvido como projeto de estagio.

---

## Sobre o projeto

O AgendaPRO e um sistema web que permite ao usuario criar uma conta, fazer login e gerenciar suas proprias atividades. Cada usuario so consegue ver e manipular as atividades que ele mesmo criou.

O objetivo foi desenvolver um sistema funcional do zero, aplicando conceitos de autenticacao, CRUD completo com requisicoes AJAX e exibicao de dados em calendario interativo.

---

## Tecnologias utilizadas

**Back-end**
- PHP 8.1
- CodeIgniter 4 — framework MVC para PHP
- MySQL — banco de dados relacional

**Front-end**
- HTML e CSS puro
- JavaScript com jQuery — para as requisicoes AJAX sem recarregar a pagina
- FullCalendar 6 — biblioteca para exibicao do calendario interativo

---

## Funcionalidades

- Cadastro de usuario com senha criptografada (bcrypt)
- Login e logout com controle de sessao
- Cada usuario ve apenas suas proprias atividades
- Criar, visualizar, editar e excluir atividades
- Alterar o status de uma atividade (Pendente, Concluida, Cancelada)
- Filtrar atividades por status ou por nome/descricao
- Visualizar atividades em um calendario mensal, semanal ou em lista

---

## Estrutura do banco de dados

**Tabela: usuarios**

| Campo     | Tipo         | Descricao                  |
|-----------|--------------|----------------------------|
| id        | INT          | Chave primaria             |
| login     | VARCHAR(60)  | Login unico do usuario     |
| senha     | VARCHAR(255) | Senha criptografada bcrypt |
| criado_em | TIMESTAMP    | Data de cadastro           |

**Tabela: atividades**

| Campo         | Tipo                              | Descricao                        |
|---------------|-----------------------------------|----------------------------------|
| id            | INT                               | Chave primaria                   |
| usuario_id    | INT                               | Chave estrangeira para usuarios  |
| nome          | VARCHAR(120)                      | Nome da atividade                |
| descricao     | TEXT                              | Descricao detalhada              |
| inicio        | DATETIME                          | Data e hora de inicio            |
| fim           | DATETIME                          | Data e hora de termino           |
| status        | ENUM(pendente, concluida, cancelada) | Status atual               |
| criado_em     | TIMESTAMP                         | Data de criacao                  |
| atualizado_em | TIMESTAMP                         | Data da ultima alteracao         |

---

## Como executar o projeto

### Pre-requisitos

Antes de rodar o projeto, voce precisa ter instalado na sua maquina:

1. **XAMPP** — instala o PHP, MySQL e Apache juntos
   - Download: https://www.apachefriends.org
   - Instale e abra o painel do XAMPP, ative os modulos **Apache** e **MySQL**

2. **Composer** — gerenciador de dependencias do PHP
   - Download: https://getcomposer.org/download
   - No Windows, baixe e execute o instalador `.exe`

---

### Passo a passo

**1. Criar o projeto CodeIgniter 4**

Abra o terminal (Prompt de Comando ou terminal do VS Code) e execute:

```
cd C:/xampp/htdocs
composer create-project codeigniter4/appstarter agenda_pro
```

**2. Copiar os arquivos do projeto**

Extraia o ZIP deste projeto e copie cada arquivo para a pasta correspondente dentro de `C:/xampp/htdocs/agenda_pro/`:

```
sql/agenda_pro.sql              -> pasta sql/ (crie se nao existir)
app/Config/Database.php         -> app/Config/
app/Config/Routes.php           -> app/Config/
app/Controllers/AuthController.php      -> app/Controllers/
app/Controllers/AtividadeController.php -> app/Controllers/
app/Models/UsuarioModel.php     -> app/Models/
app/Models/AtividadeModel.php   -> app/Models/
app/Views/                      -> app/Views/ (copie as subpastas inteiras)
public/css/app.css              -> public/css/
public/js/                      -> public/js/ (copie os tres arquivos .js)
```

**3. Criar o banco de dados**

- Abra o navegador e acesse: http://localhost/phpmyadmin
- Crie um banco chamado `agenda_pro`
- Clique em "Importar" e selecione o arquivo `sql/agenda_pro.sql`
- Clique em "Executar"

**4. Configurar a conexao com o banco**

Abra o arquivo `app/Config/Database.php` e verifique:

```php
'hostname' => 'localhost',
'username' => 'root',
'password' => '',        // no XAMPP padrao a senha e vazia
'database' => 'agenda_pro',
```

**5. Configurar o arquivo .env**

Na pasta raiz do projeto, copie o arquivo `env` para `.env`:

```
cp env .env
```

Abra o `.env` e altere:

```
CI_ENVIRONMENT = development
app.baseURL = 'http://localhost:8080/'
```

**6. Rodar o servidor**

No terminal, dentro da pasta do projeto:

```
cd C:/xampp/htdocs/agenda_pro
php spark serve
```

Acesse no navegador: **http://localhost:8080**

---

## Usuario de demonstracao

Um usuario de teste ja e inserido automaticamente pelo SQL:

| Login | Senha  |
|-------|--------|
| demo  | 123456 |

---

## Rotas da aplicacao

| Rota                       | Metodo | Descricao                          |
|----------------------------|--------|------------------------------------|
| /login                     | GET    | Tela de login                      |
| /login                     | POST   | Autenticar usuario                 |
| /cadastro                  | GET    | Tela de cadastro                   |
| /cadastro                  | POST   | Criar novo usuario                 |
| /logout                    | GET    | Encerrar sessao                    |
| /agenda                    | GET    | Lista de atividades do usuario     |
| /agenda/calendario         | GET    | Visualizacao em calendario         |
| /agenda/eventos            | GET    | Retorna eventos em JSON            |
| /atividade/store           | POST   | Criar atividade (AJAX)             |
| /atividade/:id             | GET    | Buscar atividade por ID (AJAX)     |
| /atividade/update/:id      | POST   | Editar atividade (AJAX)            |
| /atividade/destroy/:id     | POST   | Excluir atividade (AJAX)           |
| /atividade/status/:id      | POST   | Alterar status (AJAX)              |
