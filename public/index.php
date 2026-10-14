<?php

declare(strict_types=1);

use App\AplicacaoHttp;

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

$resposta = $aplicacao->responder(
    $metodo,
    $caminho,
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
