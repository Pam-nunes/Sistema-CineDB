<?php
/**
 * Funções auxiliares usadas pelas páginas.
 */

/** Escapa texto para exibição segura no HTML (evita XSS). */
function e($valor): string
{
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

/** Redireciona e encerra o script. */
function redirecionar(string $url): void
{
    header('Location: ' . $url);
    exit;
}

/** Guarda uma mensagem para ser exibida na próxima página. */
function flash(string $tipo, string $mensagem): void
{
    $_SESSION['flash'] = ['tipo' => $tipo, 'mensagem' => $mensagem];
}

/** Lê (e remove) a mensagem guardada. */
function pegarFlash(): ?array
{
    $flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $flash;
}

/** Verifica se o valor contém apenas dígitos (requisito 2.7). */
function apenasNumeros($valor): bool
{
    return is_string($valor) && $valor !== '' && ctype_digit($valor);
}

/** Lê um ID numérico da URL ou do formulário. */
function lerId(array $origem, string $campo = 'id'): ?int
{
    $valor = $origem[$campo] ?? '';
    return apenasNumeros($valor) ? (int) $valor : null;
}

/** Formata data AAAA-MM-DD para DD/MM/AAAA. */
function dataBr(?string $data): string
{
    if (!$data) {
        return '';
    }
    $d = DateTime::createFromFormat('Y-m-d', $data);
    return $d ? $d->format('d/m/Y') : $data;
}

/** Valida data no formato AAAA-MM-DD. */
function dataValida(string $data): bool
{
    $d = DateTime::createFromFormat('Y-m-d', $data);
    return $d && $d->format('Y-m-d') === $data;
}

/** Textos de exibição dos campos ENUM. */
function rotuloSexo(string $sexo): string
{
    return ['M' => 'Masculino', 'F' => 'Feminino', 'O' => 'Outro'][$sexo] ?? $sexo;
}

const SEXOS = ['M' => 'Masculino', 'F' => 'Feminino', 'O' => 'Outro'];
const CLASSIFICACOES = ['L' => 'Livre', '10' => '10 anos', '12' => '12 anos', '14' => '14 anos', '16' => '16 anos', '18' => '18 anos'];

/** Classe Bootstrap da cor de cada classificação indicativa. */
function corClassificacao(string $c): string
{
    return ['L' => 'success', '10' => 'info', '12' => 'warning', '14' => 'orange', '16' => 'danger', '18' => 'dark'][$c] ?? 'secondary';
}

/** Retorna true se a exceção foi causada por chave estrangeira em uso. */
function erroRegistroEmUso(mysqli_sql_exception $ex): bool
{
    return $ex->getCode() === 1451;
}

/** Retorna true se a exceção foi causada por valor duplicado (UNIQUE). */
function erroDuplicado(mysqli_sql_exception $ex): bool
{
    return $ex->getCode() === 1062;
}
