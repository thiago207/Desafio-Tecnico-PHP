-- ============================================================
-- AgendaPRO — Script de criação do banco de dados
-- ============================================================

CREATE DATABASE IF NOT EXISTS agenda_pro
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE agenda_pro;

-- Tabela de usuários
CREATE TABLE usuarios (
    id       INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    login    VARCHAR(60)  NOT NULL UNIQUE,
    senha    VARCHAR(255) NOT NULL,          -- bcrypt
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela de atividades
CREATE TABLE atividades (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    usuario_id  INT UNSIGNED NOT NULL,
    nome        VARCHAR(120) NOT NULL,
    descricao   TEXT,
    inicio      DATETIME     NOT NULL,
    fim         DATETIME     NOT NULL,
    status      ENUM('pendente','concluida','cancelada') NOT NULL DEFAULT 'pendente',
    criado_em   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Usuário de demonstração (senha: 123456)
INSERT INTO usuarios (login, senha) VALUES
  ('demo', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');
