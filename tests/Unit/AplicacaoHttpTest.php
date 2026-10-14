<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\AplicacaoHttp;
use App\RequisicaoHttp;
use PHPUnit\Framework\TestCase;

final class AplicacaoHttpTest extends TestCase
{
    public function testGetNaRaizRetornaPaginaInicial(): void
    {
        // Arrange
        $aplicacao = new AplicacaoHttp();

        $requisicao = new RequisicaoHttp(
            'GET',
            '/',
        );

        // Act
        $resposta = $aplicacao->responder(
            $requisicao,
        );

        // Assert
        $this->assertSame(
            200,
            $resposta->status(),
        );

        $this->assertSame(
            'text/html; charset=UTF-8',
            $resposta->cabecalhos()['Content-Type'],
        );

        $this->assertStringContainsString(
            'Treino Academia',
            $resposta->corpo(),
        );
    }

    public function testGetEmStatusRetornaEstadoDaAplicacao(): void
    {
        // Arrange
        $aplicacao = new AplicacaoHttp();

        // Act
        $requisicao = new RequisicaoHttp(
            'GET',
            '/status',
        );

        $resposta = $aplicacao->responder(
            $requisicao,
        );

        $dados = $this->decodificarJson(
            $resposta->corpo(),
        );

        // Assert
        $this->assertSame(
            200,
            $resposta->status(),
        );

        $this->assertSame(
            'application/json; charset=UTF-8',
            $resposta->cabecalhos()['Content-Type'],
        );

        $this->assertSame(
            'ok',
            $dados['status'],
        );

        $this->assertSame(
            PHP_VERSION,
            $dados['php'],
        );
    }

    public function testCaminhoInexistenteRetornaNaoEncontrado(): void
    {
        // Arrange
        $aplicacao = new AplicacaoHttp();

        // Act
        $requisicao = new RequisicaoHttp(
            'GET',
            '/nao-existe',
        );

        $resposta = $aplicacao->responder(
            $requisicao,
        );

        $dados = $this->decodificarJson(
            $resposta->corpo(),
        );

        // Assert
        $this->assertSame(
            404,
            $resposta->status(),
        );

        $this->assertSame(
            'Recurso não encontrado.',
            $dados['erro'],
        );
    }

    public function testPostEmStatusRetornaMetodoNaoPermitido(): void
    {
        // Arrange
        $aplicacao = new AplicacaoHttp();

        // Act
        $requisicao = new RequisicaoHttp(
            'POST',
            '/status',
        );

        $resposta = $aplicacao->responder(
            $requisicao,
        );

        $dados = $this->decodificarJson(
            $resposta->corpo(),
        );

        // Assert
        $this->assertSame(
            405,
            $resposta->status(),
        );

        $this->assertSame(
            'GET',
            $resposta->cabecalhos()['Allow'],
        );

        $this->assertSame(
            'Método não permitido.',
            $dados['erro'],
        );
    }

    /**
     * @return array<string, mixed>
     */
    private function decodificarJson(string $json): array
    {
        $dados = json_decode(
            $json,
            true,
            flags: JSON_THROW_ON_ERROR,
        );

        if (!is_array($dados)) {
            $this->fail(
                'Era esperada uma resposta JSON representada por um array.',
            );
        }

        $resultado = [];

        foreach ($dados as $chave => $valor) {
            if (!is_string($chave)) {
                $this->fail(
                    'Era esperado um objeto JSON com chaves textuais.',
                );
            }

            $resultado[$chave] = $valor;
        }

        return $resultado;
    }
}
