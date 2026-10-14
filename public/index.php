<?php

declare(strict_types=1);

$metodo = $_SERVER['REQUEST_METHOD'];
$caminho = parse_url(
    $_SERVER['REQUEST_URI'],
    PHP_URL_PATH
);

if ($metodo === 'GET' && $caminho === '/') {
    http_response_code(200);

    header('Content-Type: text/html; charset=UTF-8');

?>
    <!DOCTYPE html>
    <html lang="pt-BR">

    <head>
        <meta charset="UTF-8">
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1.0">

        <title>Treino Academia</title>
    </head>

    <body>
        <main>
            <h1>Treino Academia</h1>

            <p>
                Primeira aplicação web executada com
                PHP 8.4 dentro do Docker.
            </p>
        </main>
    </body>

    </html>
<?php

    exit;
}

if ($caminho === '/status') {
    if ($metodo !== 'GET') {
        http_response_code(405);

        header('Content-Type: application/json; charset=UTF-8');
        header('Allow: GET');

        echo json_encode(
            [
                'erro' => 'Método não permitido.',
            ],
            JSON_THROW_ON_ERROR
        );

        exit;
    }

    http_response_code(200);

    header('Content-Type: application/json; charset=UTF-8');

    echo json_encode(
        [
            'status' => 'ok',
            'php' => PHP_VERSION,
        ],
        JSON_THROW_ON_ERROR
    );

    exit;
}

http_response_code(404);

header('Content-Type: application/json; charset=UTF-8');

echo json_encode(
    [
        'erro' => 'Recurso não encontrado.',
    ],
    JSON_THROW_ON_ERROR
);
