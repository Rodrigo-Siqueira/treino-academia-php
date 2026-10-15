<?php

declare(strict_types=1);

use App\AplicacaoHttp;
use App\RequisicaoHttp;
use App\ConsultaBancoDeDadosPdo;
use App\RespostaHttp;

require dirname(__DIR__) . '/vendor/autoload.php';

$metodo = $_SERVER['REQUEST_METHOD'];

$caminho = parse_url(
    $_SERVER['REQUEST_URI'],
    PHP_URL_PATH,
);

if (!is_string($caminho)) {
    $caminho = '/';
}

parse_str(
    $_SERVER['QUERY_STRING'] ?? '',
    $parametrosBrutos,
);

/** @var array<string, string> $parametrosDeConsulta */
$parametrosDeConsulta = [];

foreach ($parametrosBrutos as $nome => $valor) {
    if (
        is_string($nome)
        && is_string($valor)
    ) {
        $parametrosDeConsulta[$nome] = $valor;
    }
}

$requisicao = new RequisicaoHttp(
    $metodo,
    $caminho,
    $parametrosDeConsulta,
);

$host = getenv('POSTGRES_HOST') ?: 'db';
$porta = getenv('POSTGRES_PORT') ?: '5432';
$banco = getenv('POSTGRES_DB') ?: 'treino_academia';
$usuario = getenv('POSTGRES_USER') ?: 'treino_app';
$senha = getenv('POSTGRES_PASSWORD') ?: '';

try {
    $host = getenv('POSTGRES_HOST') ?: 'db';
    $porta = getenv('POSTGRES_PORT') ?: '5432';
    $banco = getenv('POSTGRES_DB') ?: 'treino_academia';
    $usuario = getenv('POSTGRES_USER') ?: 'treino_app';
    $senha = getenv('POSTGRES_PASSWORD') ?: '';

    $pdo = new PDO(
        sprintf(
            'pgsql:host=%s;port=%s;dbname=%s',
            $host,
            $porta,
            $banco,
        ),
        $usuario,
        $senha,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ],
    );

    $consultaBancoDeDados = new ConsultaBancoDeDadosPdo(
        $pdo,
    );

    $aplicacao = new AplicacaoHttp(
        $consultaBancoDeDados,
    );

    $resposta = $aplicacao->responder(
        $requisicao,
    );
} catch (Throwable $erro) {
    error_log(
        sprintf(
            '%s: %s',
            $erro::class,
            $erro->getMessage(),
        ),
    );

    $resposta = new RespostaHttp(
        500,
        [
            'Content-Type' => 'application/json; charset=UTF-8',
        ],
        json_encode(
            [
                'erro' => 'Erro interno do servidor.',
            ],
            JSON_THROW_ON_ERROR,
        ),
    );
}

http_response_code(
    $resposta->status(),
);

foreach (
    $resposta->cabecalhos()
    as $nome => $valor
) {
    header($nome . ': ' . $valor);
}

echo $resposta->corpo();
