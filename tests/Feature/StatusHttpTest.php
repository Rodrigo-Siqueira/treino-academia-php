<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

final class StatusHttpTest extends TestCase
{
    public function testRetornaEstadoDaAplicacao(): void
    {
        // Act
        $resposta = $this->getJson('/status');

        // Assert
        $resposta->assertOk();

        $resposta->assertJson([
            'status' => 'ok',
            'php' => PHP_VERSION,
        ]);
    }

    public function testRejeitaPostNoStatus(): void
    {
        // Act
        $resposta = $this->postJson('/status');

        // Assert
        $resposta->assertStatus(405);
    }
}
