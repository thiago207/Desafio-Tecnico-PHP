# Desafio Tecnico PHP — Agenda Eletronica

Projeto desenvolvido como parte do processo seletivo para a vaga de Desenvolvedor PHP.

---

## Sobre o projeto

Sistema de agenda eletronica web que permite o cadastro de usuarios e o gerenciamento de atividades. Cada usuario possui acesso exclusivo as suas proprias atividades, sem visualizar as de outros usuarios.

---

## Funcionalidades

- Cadastro e login de usuario com senha criptografada
- Cada usuario visualiza apenas suas proprias atividades
- Criar, visualizar, editar e excluir atividades (CRUD completo)
- Alteracao de status da atividade apos a criacao (Pendente, Concluida, Cancelada)
- Filtro de atividades por status e por nome ou descricao
- Exibicao das atividades em calendario interativo

---

## Estrutura do banco de dados

**Tabela: usuarios**

| Campo     | Tipo         | Descricao                       |
|-----------|--------------|---------------------------------|
| id        | INT          | Chave primaria, auto incremento |
| login     | VARCHAR(60)  | Login unico do usuario          |
| senha     | VARCHAR(255) | Senha criptografada com bcrypt  |
| criado_em | TIMESTAMP    | Data de criacao do registro     |

**Tabela: atividades**

| Campo         | Tipo                                    | Descricao                           |
|---------------|-----------------------------------------|-------------------------------------|
| id            | INT                                     | Chave primaria, auto incremento     |
| usuario_id    | INT                                     | Chave estrangeira para usuarios     |
| nome          | VARCHAR(120)                            | Nome da atividade                   |
| descricao     | TEXT                                    | Descricao detalhada                 |
| inicio        | DATETIME                                | Data e hora de inicio               |
| fim           | DATETIME                                | Data e hora de termino              |
| status        | ENUM(pendente, concluida, cancelada)    | Status atual da atividade           |
| criado_em     | TIMESTAMP                               | Data de criacao                     |
| atualizado_em | TIMESTAMP                               | Data da ultima atualizacao          |

---

## Tecnologias utilizadas

| Tecnologia   | Versao  |
|--------------|---------|
| PHP          | 8.2     |
| CodeIgniter  | 4.7.2   |
| MySQL        | 8.0     |
| Bootstrap    | 5.3.3   |
| jQuery       | 3.7.1   |
| FullCalendar | 6.1.11  |

---

## Como executar o projeto

### Pre-requisitos

- XAMPP com PHP 8.2+ e MySQL
- Composer

### Passo a passo

**1. Clonar o repositorio**

```
git clone https://github.com/thiago207/Desafio-Tecnico-PHP.git
cd Desafio-Tecnico-PHP
```

**2. Instalar as dependencias**

```
composer install
```

**3. Criar o banco de dados**

Acesse o phpMyAdmin, crie um banco chamado `agenda_pro` e importe o arquivo:

```
sql/agenda_pro.sql
```

**4. Configurar o ambiente**

Copie o arquivo `env` para `.env` e edite as seguintes linhas:

```
CI_ENVIRONMENT = development
app.baseURL = 'http://localhost:8080/'

database.default.hostname = localhost
database.default.database = agenda_pro
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi
```

**5. Iniciar o servidor**

```
php spark serve
```

Acesse no navegador: http://localhost:8080

---

## Usuario de demonstracao

| Login | Senha  |
|-------|--------|
| demo  | 123456 |