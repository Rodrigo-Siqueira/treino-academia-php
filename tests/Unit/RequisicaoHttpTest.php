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

}
