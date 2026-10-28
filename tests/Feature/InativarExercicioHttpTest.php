<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\ExercicioModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class InativarExercicioHttpTest extends TestCase
{
    use RefreshDatabase;

    public function testInativaExercicioAtivo(): void
    {
        // Arrange
        $exercicio = ExercicioModel::create([
            'nome' => 'Supino reto',
            'ativo' => true,
        ]);

        // Act
        $resposta = $this->patchJson(
            '/api/exercicios/' . $exercicio->id . '/inativacao',
        );

        // Assert
        $resposta->assertOk();

        $resposta->assertExactJson([
            'id' => $exercicio->id,
            'nome' => 'Supino reto',
            'ativo' => false,
        ]);

        $this->assertDatabaseHas(
            'exercicios',
            [
                'id' => $exercicio->id,
                'ativo' => false,
            ],
        );
    }

    public function testRetornaNaoEncontradoParaExercicioInexistente(): void
    {
        // Act
        $resposta = $this->patchJson(
            '/api/exercicios/999999/inativacao',
        );

        // Assert
        $resposta->assertNotFound();
    }

    public function testRejeitaInativacaoDeExercicioJaInativo(): void
    {
        // Arrange
        $exercicio = ExercicioModel::create([
            'nome' => 'Supino reto',
            'ativo' => false,
        ]);

        // Act
        $resposta = $this->patchJson(
            '/api/exercicios/' . $exercicio->id . '/inativacao',
        );

        // Assert
        $resposta->assertStatus(409);

        $resposta->assertExactJson([
            'erro' => 'Exercício já está inativo.',
        ]);

        $this->assertDatabaseHas(
            'exercicios',
            [
                'id' => $exercicio->id,
                'ativo' => false,
            ],
        );
    }
}
