<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\ExercicioModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class ExercicioModelTest extends TestCase
{
    use RefreshDatabase;

    public function testPersisteExercicioAtivo(): void
    {
        // Act
        $exercicio = ExercicioModel::create([
            'nome' => 'Supino reto',
            'ativo' => true,
        ]);

        // Assert
        $this->assertGreaterThan(
            0,
            $exercicio->id,
        );

        $this->assertSame(
            'Supino reto',
            $exercicio->nome,
        );

        $this->assertTrue(
            $exercicio->ativo,
        );

        $this->assertDatabaseHas(
            'exercicios',
            [
                'id' => $exercicio->id,
                'nome' => 'Supino reto',
                'ativo' => true,
            ],
        );
    }
}
