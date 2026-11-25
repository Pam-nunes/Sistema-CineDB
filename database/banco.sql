-- =====================================================================
-- Projeto Físico do Banco de Dados - CineDB (Filmes e Atores)
-- SGBD: MySQL 5.7+ / MariaDB 10.3+
-- Execute este script inteiro (ex.: no phpMyAdmin, aba "SQL").
-- =====================================================================

SET NAMES utf8mb4;

DROP DATABASE IF EXISTS cinedb;
CREATE DATABASE cinedb CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE cinedb;

-- ---------------------------------------------------------------------
-- Nacionalidade (2.4) - exige apenas o nome
-- ---------------------------------------------------------------------
CREATE TABLE nacionalidade (
    id   INT UNSIGNED NOT NULL AUTO_INCREMENT,
    nome VARCHAR(60)  NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uk_nacionalidade_nome (nome)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- Categoria (2.5 - desejável)
-- ---------------------------------------------------------------------
CREATE TABLE categoria (
    id   INT UNSIGNED NOT NULL AUTO_INCREMENT,
    nome VARCHAR(60)  NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uk_categoria_nome (nome)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- Idioma (2.5 - desejável)
-- ---------------------------------------------------------------------
CREATE TABLE idioma (
    id   INT UNSIGNED NOT NULL AUTO_INCREMENT,
    nome VARCHAR(60)  NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uk_idioma_nome (nome)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- Ator (2.1) - nacionalidade OBRIGATÓRIA (NOT NULL)
-- ---------------------------------------------------------------------
CREATE TABLE ator (
    id               INT UNSIGNED NOT NULL AUTO_INCREMENT,
    nome             VARCHAR(60)  NOT NULL,
    sobrenome        VARCHAR(80)  NOT NULL,
    data_nascimento  DATE         NOT NULL,
    sexo             ENUM('M','F','O') NOT NULL,
    nacionalidade_id INT UNSIGNED NOT NULL,
    PRIMARY KEY (id),
    KEY idx_ator_nome (nome, sobrenome),
    CONSTRAINT fk_ator_nacionalidade
        FOREIGN KEY (nacionalidade_id) REFERENCES nacionalidade (id)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- Filme (2.2) - nacionalidade OPCIONAL (NULL)
-- ---------------------------------------------------------------------
CREATE TABLE filme (
    id                  INT UNSIGNED NOT NULL AUTO_INCREMENT,
    titulo              VARCHAR(150) NOT NULL,
    descricao           TEXT         NOT NULL,
    ano_lancamento      SMALLINT UNSIGNED NOT NULL,
    classificacao       ENUM('L','10','12','14','16','18') NOT NULL,
    categoria_id        INT UNSIGNED NOT NULL,
    idioma_id           INT UNSIGNED NOT NULL,
    nacionalidade_id    INT UNSIGNED NULL,
    PRIMARY KEY (id),
    KEY idx_filme_titulo (titulo),
    CONSTRAINT fk_filme_categoria
        FOREIGN KEY (categoria_id) REFERENCES categoria (id)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_filme_idioma
        FOREIGN KEY (idioma_id) REFERENCES idioma (id)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_filme_nacionalidade
        FOREIGN KEY (nacionalidade_id) REFERENCES nacionalidade (id)
        ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- Filme x Ator (2.3) - relacionamento N:N
-- ---------------------------------------------------------------------
CREATE TABLE filme_ator (
    filme_id INT UNSIGNED NOT NULL,
    ator_id  INT UNSIGNED NOT NULL,
    PRIMARY KEY (filme_id, ator_id),
    KEY idx_filme_ator_ator (ator_id),
    CONSTRAINT fk_filme_ator_filme
        FOREIGN KEY (filme_id) REFERENCES filme (id)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_filme_ator_ator
        FOREIGN KEY (ator_id) REFERENCES ator (id)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;

-- =====================================================================
-- Dados de exemplo
-- =====================================================================
INSERT INTO nacionalidade (nome) VALUES
    ('Brasileira'), ('Americana'), ('Britânica'), ('Francesa'),
    ('Australiana'), ('Canadense'), ('Coreana');

INSERT INTO categoria (nome) VALUES
    ('Ação'), ('Drama'), ('Comédia'), ('Ficção Científica'),
    ('Suspense'), ('Animação'), ('Romance');

INSERT INTO idioma (nome) VALUES
    ('Português'), ('Inglês'), ('Francês'), ('Coreano'), ('Espanhol');

INSERT INTO ator (nome, sobrenome, data_nascimento, sexo, nacionalidade_id) VALUES
    ('Fernanda',  'Montenegro', '1929-10-16', 'F', 1),
    ('Wagner',    'Moura',      '1976-06-27', 'M', 1),
    ('Leonardo',  'DiCaprio',   '1974-11-11', 'M', 2),
    ('Margot',    'Robbie',     '1990-07-02', 'F', 5),
    ('Cillian',   'Murphy',     '1976-05-25', 'M', 3),
    ('Song',      'Kang-ho',    '1967-01-17', 'M', 7),
    ('Selton',    'Mello',      '1972-12-30', 'M', 1),
    ('Fernanda',  'Torres',     '1965-09-15', 'F', 1);

INSERT INTO filme (titulo, descricao, ano_lancamento, classificacao, categoria_id, idioma_id, nacionalidade_id) VALUES
    ('Central do Brasil', 'Uma ex-professora ajuda um menino a procurar o pai no Nordeste.', 1998, '12', 2, 1, 1),
    ('Tropa de Elite', 'O dia a dia do BOPE no Rio de Janeiro sob o olhar do Capitão Nascimento.', 2007, '16', 1, 1, 1),
    ('O Auto da Compadecida', 'As aventuras de João Grilo e Chicó no sertão nordestino.', 2000, '12', 3, 1, 1),
    ('A Origem', 'Um ladrão invade sonhos para roubar segredos do subconsciente.', 2010, '14', 4, 2, 2),
    ('O Lobo de Wall Street', 'A ascensão e queda de um corretor da bolsa de Nova York.', 2013, '18', 3, 2, 2),
    ('Oppenheimer', 'A história do físico responsável pelo Projeto Manhattan.', 2023, '16', 2, 2, NULL),
    ('Parasita', 'Uma família pobre se infiltra na casa de uma família rica.', 2019, '16', 5, 4, 7),
    ('Ainda Estou Aqui', 'Eunice Paiva enfrenta a ditadura após o desaparecimento do marido.', 2024, '14', 2, 1, 1);

INSERT INTO filme_ator (filme_id, ator_id) VALUES
    (1, 1),
    (2, 2),
    (3, 7),
    (4, 3), (4, 5),
    (5, 3), (5, 4),
    (6, 5),
    (7, 6),
    (8, 8), (8, 7), (8, 1);
