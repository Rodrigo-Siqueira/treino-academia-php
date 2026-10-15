<?php

declare(strict_types=1);

namespace App;

final class AplicacaoHttp
{
    public function __construct(
        private ConsultaBancoDeDados $consultaBancoDeDados,
    ) {
    }

    public function responder(
        RequisicaoHttp $requisicao,
    ): RespostaHttp {
        $metodo = $requisicao->metodo();
        $caminho = $requisicao->caminho();

        if ($metodo === 'GET' && $caminho === '/') {
            return new RespostaHttp(
                200,
                [
                    'Content-Type' => 'text/html; charset=UTF-8',
                ],
                $this->paginaInicial(),
            );
        }

        if (
            $caminho === '/status'
            && $metodo !== 'GET'
        ) {
            return new RespostaHttp(
                405,
                [
                    'Content-Type' => 'application/json; charset=UTF-8',
                    'Allow' => 'GET',
                ],
                json_encode(
                    [
                        'erro' => 'Método não permitido.',
                    ],
                    JSON_THROW_ON_ERROR,
                ),
            );
        }

        if ($metodo === 'GET' && $caminho === '/status') {
            return new RespostaHttp(
                200,
                [
                    'Content-Type' => 'application/json; charset=UTF-8',
                ],
                json_encode(
                    [
                        'status' => 'ok',
                        'php' => PHP_VERSION,
                    ],
                    JSON_THROW_ON_ERROR,
                ),
            );
        }

        if ($metodo === 'GET' && $caminho === '/saudacao') {
            $nome = $requisicao->parametroDeConsulta('nome');

            if ($nome === null || trim($nome) === '') {
                return new RespostaHttp(
                    400,
                    [
                        'Content-Type' => 'application/json; charset=UTF-8',
                    ],
                    json_encode(
                        [
                            'erro' => 'O parâmetro nome é obrigatório.',
                        ],
                        JSON_THROW_ON_ERROR,
                    ),
                );
            }

            $nomeSeguro = htmlspecialchars(
                trim($nome),
                ENT_QUOTES | ENT_SUBSTITUTE,
                'UTF-8',
            );

            return new RespostaHttp(
                200,
                [
                    'Content-Type' => 'text/html; charset=UTF-8',
                ],
                sprintf(
                    '<h1>Olá, %s!</h1>',
                    $nomeSeguro,
                ),
            );
        }

        if ($metodo === 'GET' && $caminho === '/banco') {
            return new RespostaHttp(
                200,
                [
                    'Content-Type' => 'application/json; charset=UTF-8',
                ],
                json_encode(
                    [
                        'banco' => $this
                            ->consultaBancoDeDados
                            ->nomeDoBanco(),
                    ],
                    JSON_THROW_ON_ERROR,
                ),
            );
        }

        return new RespostaHttp(
            404,
            [
                'Content-Type' => 'application/json; charset=UTF-8',
            ],
            json_encode(
                [
                    'erro' => 'Recurso não encontrado.',
                ],
                JSON_THROW_ON_ERROR,
            ),
        );
    }

    private function paginaInicial(): string
    {
        return <<<'HTML'
            <!DOCTYPE html>
            <html lang="pt-BR">
            <head>
                <meta charset="UTF-8">
                <meta
                    name="viewport"
                    content="width=device-width, initial-scale=1.0"
                >

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
            HTML;
    }
}
