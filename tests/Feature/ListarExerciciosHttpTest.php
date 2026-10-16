<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\ExercicioModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class ListarExerciciosHttpTest extends TestCase
{
    use RefreshDatabase;

    public function testRetornaListaVaziaSemExercicios(): void
    {
        // Act
        $resposta = $this->getJson(
            '/api/exercicios',
        );

        // Assert
        $resposta->assertOk();

        $resposta->assertExactJson([]);
    }

    public function testRetornaExerciciosOrdenadosPeloIdentificador(): void
    {
        // Arrange
        $primeiroExercicio = ExercicioModel::create([
            'nome' => 'Supino reto',
            'ativo' => true,
        ]);

        $segundoExercicio = ExercicioModel::create([
            'nome' => 'Remada sentada',
            'ativo' => false,
        ]);

        // Act
        $resposta = $this->getJson(
            '/api/exercicios',
        );

        // Assert
        $resposta->assertOk();

        $resposta->assertExactJson([
            [
                'id' => $primeiroExercicio->id,
                'nome' => 'Supino reto',
                'ativo' => true,
            ],
            [
                'id' => $segundoExercicio->id,
                'nome' => 'Remada sentada',
                'ativo' => false,
            ],
        ]);
    }
}
