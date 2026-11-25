# CineDB — Banco de Filmes e Atores

Trabalho Prático do curso de Análise e Desenvolvimento de Sistemas.
Website para gerenciar filmes, atores e seus elencos, feito com **PHP puro + MySQL (MySQLi orientado a objeto)** e **Bootstrap 5**.

## Funcionalidades

| Requisito | Onde |
|---|---|
| 2.1 CRUD de atores (nome, sobrenome, nacionalidade, nascimento, sexo) | `atores.php`, `ator_form.php` |
| 2.1b Ator com nacionalidade obrigatória | `NOT NULL` + validação em `Ator::validar()` |
| 2.2 CRUD de filmes (título, descrição, ano, categoria, idioma, classificação) | `filmes.php`, `filme_form.php` |
| 2.2b Filme com nacionalidade opcional | coluna `NULL` + opção "Não informada" |
| 2.3 Atores ↔ filmes (N:N) | tabela `filme_ator`, elenco escolhido no formulário do filme |
| 2.4 CRUD de nacionalidade | `nacionalidades.php` |
| 2.5 CRUD de categoria e idioma | `categorias.php`, `idiomas.php` |
| 2.6 Página de atores e seus filmes com filtro por ator e por filme | `elenco.php` |
| 2.7 Campos numéricos aceitam apenas números | ano de lançamento (bloqueio no JS + validação no PHP) |
| 2.8 Estilização com Bootstrap / CSS puro | Bootstrap 5 (local, em `assets/vendor`) + `assets/css/style.css` |

Extras: busca em todas as listagens, confirmação antes de excluir, proteção contra exclusão de itens em uso (chave estrangeira), consultas com *prepared statements* (contra SQL Injection) e `htmlspecialchars` (contra XSS).

## Estrutura

```
cinedb/
├── index.php, filmes.php, ...   Páginas (controladores): recebem a requisição e escolhem a view
├── app/
│   ├── bootstrap.php            Inicialização (sessão, includes, tratamento de erros)
│   ├── Database.php             Conexão MySQLi (singleton)
│   ├── helpers.php              Funções auxiliares
│   ├── controllers/             Lógica compartilhada dos cadastros simples
│   └── models/                  Classes de acesso ao banco (todo SQL fica aqui)
├── views/                       HTML das telas (layout, listas, formulários)
├── assets/                      CSS, JS e Bootstrap
├── config/config.php            Dados de conexão com o banco
└── database/banco.sql           Projeto físico do BD (script de criação + dados de exemplo)
```

## Como rodar (XAMPP)

1. Copie a pasta `cinedb` para `C:\xampp\htdocs\`.
2. No painel do XAMPP, inicie **Apache** e **MySQL**.
3. Acesse `http://localhost/phpmyadmin`, aba **SQL** (ou **Importar**), e execute o arquivo `database/banco.sql`.
4. Abra `http://localhost/cinedb`.

Se o seu MySQL tiver senha ou outra porta, ajuste `config/config.php`.

## Modelo do banco

```
nacionalidade (id, nome)
categoria     (id, nome)
idioma        (id, nome)
ator          (id, nome, sobrenome, data_nascimento, sexo, nacionalidade_id → nacionalidade NOT NULL)
filme         (id, titulo, descricao, ano_lancamento, classificacao,
               categoria_id → categoria, idioma_id → idioma, nacionalidade_id → nacionalidade NULL)
filme_ator    (filme_id → filme, ator_id → ator)   -- relação N:N
```
