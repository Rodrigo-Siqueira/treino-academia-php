<?php

declare(strict_types=1);

use App\AplicacaoHttp;
use App\RequisicaoHttp;

require dirname(__DIR__) . '/vendor/autoload.php';

$metodo = $_SERVER['REQUEST_METHOD'];

$caminho = parse_url(
    $_SERVER['REQUEST_URI'],
    PHP_URL_PATH,
);

if (!is_string($caminho)) {
    $caminho = '/';
}

$aplicacao = new AplicacaoHttp();

$requisicao = new RequisicaoHttp(
    $metodo,
    $caminho,
);

$resposta = $aplicacao->responder(
    $requisicao,
);

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
