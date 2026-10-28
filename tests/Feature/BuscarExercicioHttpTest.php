<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\ExercicioModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class BuscarExercicioHttpTest extends TestCase
{
    use RefreshDatabase;

    public function testRetornaExercicioExistente(): void
    {
        // Arrange
        $exercicio = ExercicioModel::create([
            'nome' => 'Supino inclinado',
            'ativo' => true,
        ]);

        // Act
        $resposta = $this->getJson(
            '/api/exercicios/' . $exercicio->id,
        );

        // Assert
        $resposta->assertOk();

        $resposta->assertExactJson([
            'id' => $exercicio->id,
            'nome' => 'Supino inclinado',
            'ativo' => true,
        ]);
    }

    public function testRetornaNaoEncontradoParaExercicioInexistente(): void
    {
        // Act
        $resposta = $this->getJson(
            '/api/exercicios/999999',
        );

        // Assert
        $resposta->assertNotFound();
    }
}
