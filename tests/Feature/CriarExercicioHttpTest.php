<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class CriarExercicioHttpTest extends TestCase
{
    use RefreshDatabase;

    public function testCriaExercicioAtivo(): void
    {
        // Act
        $resposta = $this->postJson(
            '/api/exercicios',
            [
                'nome' => 'Supino reto',
            ],
        );

        // Assert
        $resposta->assertCreated();

        $resposta->assertJson([
            'nome' => 'Supino reto',
            'ativo' => true,
        ]);

        $this->assertDatabaseHas(
            'exercicios',
            [
                'nome' => 'Supino reto',
                'ativo' => true,
            ],
        );
    }

    public function testRejeitaNomeAusente(): void
    {
        // Act
        $resposta = $this->postJson(
            '/api/exercicios',
            [],
        );

        // Assert
        $resposta->assertUnprocessable();

        $resposta->assertJsonValidationErrors([
            'nome',
        ]);

        $this->assertDatabaseCount(
            'exercicios',
            0,
        );
    }

    public function testRejeitaNomeVazio(): void
    {
        // Act
        $resposta = $this->postJson(
            '/api/exercicios',
            [
                'nome' => '',
            ],
        );

        // Assert
        $resposta->assertUnprocessable();

        $resposta->assertJsonValidationErrors([
            'nome',
        ]);

        $this->assertDatabaseCount(
            'exercicios',
            0,
        );
    }
    public function testRejeitaNomeComApenasEspacos(): void
    {
        // Act
        $resposta = $this->postJson(
            '/api/exercicios',
            [
                'nome' => '   ',
            ],
        );

        // Assert
        $resposta->assertUnprocessable();

        $resposta->assertJsonValidationErrors([
            'nome',
        ]);

        $this->assertDatabaseCount(
            'exercicios',
            0,
        );
    }

    public function testNormalizaEspacosDoNome(): void
    {
        // Act
        $resposta = $this->postJson(
            '/api/exercicios',
            [
                'nome' => '  Supino reto  ',
            ],
        );

        // Assert
        $resposta->assertCreated();

        $resposta->assertJson([
            'nome' => 'Supino reto',
            'ativo' => true,
        ]);

        $this->assertDatabaseHas(
            'exercicios',
            [
                'nome' => 'Supino reto',
                'ativo' => true,
            ],
        );
    }
}
