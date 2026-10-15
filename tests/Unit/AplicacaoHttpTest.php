<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\AplicacaoHttp;
use App\ConsultaBancoDeDados;
use App\RequisicaoHttp;
use PHPUnit\Framework\TestCase;
use RuntimeException;

final class AplicacaoHttpTest extends TestCase
{
    public function testGetNaRaizRetornaPaginaInicial(): void
    {
        // Arrange
        $aplicacao = $this->criarAplicacao();

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
        $aplicacao = $this->criarAplicacao();

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
        $aplicacao = $this->criarAplicacao();

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
        $aplicacao = $this->criarAplicacao();

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

    public function testSaudacaoRetornaNomeInformado(): void
    {
        // Arrange
        $aplicacao = $this->criarAplicacao();

        $requisicao = new RequisicaoHttp(
            'GET',
            '/saudacao',
            [
                'nome' => 'Rodrigo',
            ],
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
            'Olá, Rodrigo!',
            $resposta->corpo(),
        );
    }

    public function testSaudacaoSemNomeRetornaRequisicaoInvalida(): void
    {
        // Arrange
        $aplicacao = $this->criarAplicacao();

        $requisicao = new RequisicaoHttp(
            'GET',
            '/saudacao',
        );

        // Act
        $resposta = $aplicacao->responder(
            $requisicao,
        );

        $dados = $this->decodificarJson(
            $resposta->corpo(),
        );

        // Assert
        $this->assertSame(
            400,
            $resposta->status(),
        );

        $this->assertSame(
            'O parâmetro nome é obrigatório.',
            $dados['erro'],
        );
    }

    public function testSaudacaoCodificaHtmlDoNome(): void
    {
        // Arrange
        $aplicacao = $this->criarAplicacao();

        $requisicao = new RequisicaoHttp(
            'GET',
            '/saudacao',
            [
                'nome' => '<script>alert("teste")</script>',
            ],
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

        $this->assertStringNotContainsString(
            '<script>',
            $resposta->corpo(),
        );

        $this->assertStringContainsString(
            '&lt;script&gt;',
            $resposta->corpo(),
        );
    }

    public function testGetEmBancoRetornaNomeDoBanco(): void
    {
        // Arrange
        $aplicacao = $this->criarAplicacao();

        $requisicao = new RequisicaoHttp(
            'GET',
            '/banco',
        );

        // Act
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
            'treino_teste',
            $dados['banco'],
        );
    }

    public function testFalhaNaConsultaDoBancoPropagaExcecao(): void
    {
        // Arrange
        $consultaBancoDeDados = new class () implements ConsultaBancoDeDados {
            public function nomeDoBanco(): string
            {
                throw new RuntimeException(
                    'Falha interna simulada.',
                );
            }
        };

        $aplicacao = new AplicacaoHttp(
            $consultaBancoDeDados,
        );

        $requisicao = new RequisicaoHttp(
            'GET',
            '/banco',
        );

        // Assert
        $this->expectException(
            RuntimeException::class,
        );

        // Act
        $aplicacao->responder(
            $requisicao,
        );
    }

    private function criarAplicacao(): AplicacaoHttp
    {
        $consultaBancoDeDados = new class () implements ConsultaBancoDeDados {
            public function nomeDoBanco(): string
            {
                return 'treino_teste';
            }
        };

        return new AplicacaoHttp(
            $consultaBancoDeDados,
        );
    }
}
