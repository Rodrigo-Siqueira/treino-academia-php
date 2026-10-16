<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

final class SaudacaoHttpTest extends TestCase
{
    public function testRetornaSaudacaoComNomeInformado(): void
    {
        // Act
        $resposta = $this->get(
            '/saudacao?nome=Rodrigo',
        );

        // Assert
        $resposta->assertOk();

        $resposta->assertHeader(
            'Content-Type',
            'text/html; charset=UTF-8',
        );

        $resposta->assertSeeText(
            'Olá, Rodrigo!',
        );
    }

    public function testRetornaRequisicaoInvalidaSemNome(): void
    {
        // Act
        $resposta = $this->get('/saudacao');

        // Assert
        $resposta->assertStatus(400);

        $resposta->assertJson([
            'erro' => 'O parâmetro nome é obrigatório.',
        ]);
    }

    public function testRetornaRequisicaoInvalidaComNomeVazio(): void
    {
        // Act
        $resposta = $this->get(
            '/saudacao?nome=',
        );

        // Assert
        $resposta->assertStatus(400);

        $resposta->assertJson([
            'erro' => 'O parâmetro nome é obrigatório.',
        ]);
    }

    public function testRetornaRequisicaoInvalidaComNomeEmBranco(): void
    {
        // Arrange
        $queryString = http_build_query([
            'nome' => '   ',
        ]);

        // Act
        $resposta = $this->get(
            '/saudacao?' . $queryString,
        );

        // Assert
        $resposta->assertStatus(400);

        $resposta->assertJson([
            'erro' => 'O parâmetro nome é obrigatório.',
        ]);
    }

    public function testCodificaHtmlDoNome(): void
    {
        // Arrange
        $queryString = http_build_query([
            'nome' => '<script>alert("teste")</script>',
        ]);

        // Act
        $resposta = $this->get(
            '/saudacao?' . $queryString,
        );

        // Assert
        $resposta->assertOk();

        $resposta->assertDontSee(
            '<script>',
            escape: false,
        );

        $resposta->assertSee(
            '&lt;script&gt;',
            escape: false,
        );
    }
}
