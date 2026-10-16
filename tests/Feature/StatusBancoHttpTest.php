<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\ConsultaBancoDeDados;
use Tests\TestCase;

final class StatusBancoHttpTest extends TestCase
{
    public function testRetornaNomeDoBancoConectado(): void
    {
        // Arrange
        $consultaBancoDeDados = new class () implements ConsultaBancoDeDados {
            public function nomeDoBanco(): string
            {
                return 'treino_teste';
            }
        };

        $this->app->instance(
            ConsultaBancoDeDados::class,
            $consultaBancoDeDados,
        );

        // Act
        $resposta = $this->getJson('/banco');

        // Assert
        $resposta->assertOk();

        $resposta->assertJson([
            'banco' => 'treino_teste',
        ]);
    }
}
