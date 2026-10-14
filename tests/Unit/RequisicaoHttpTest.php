<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\RequisicaoHttp;
use PHPUnit\Framework\TestCase;

final class RequisicaoHttpTest extends TestCase
{
    public function testMantemMetodoECaminho(): void
    {
        // Act
        $requisicao = new RequisicaoHttp(
            'GET',
            '/status',
        );

        // Assert
        $this->assertSame(
            'GET',
            $requisicao->metodo(),
        );

        $this->assertSame(
            '/status',
            $requisicao->caminho(),
        );
    }

    public function testNormalizaMetodoParaMaiusculas(): void
    {
        // Act
        $requisicao = new RequisicaoHttp(
            'get',
            '/status',
        );

        // Assert
        $this->assertSame(
            'GET',
            $requisicao->metodo(),
        );
    }

    public function testMantemParametrosDeConsulta(): void
    {
        // Act
        $requisicao = new RequisicaoHttp(
            'GET',
            '/saudacao',
            [
                'nome' => 'Rodrigo',
            ],
        );

        // Assert
        $this->assertSame(
            'Rodrigo',
            $requisicao->parametroDeConsulta('nome'),
        );
    }

    public function testParametroDeConsultaAusenteRetornaNull(): void
    {
        // Arrange
        $requisicao = new RequisicaoHttp(
            'GET',
            '/saudacao',
        );

        // Act
        $nome = $requisicao->parametroDeConsulta('nome');

        // Assert
        $this->assertNull($nome);
    }
}
