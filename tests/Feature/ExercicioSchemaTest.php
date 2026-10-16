<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

final class ExercicioSchemaTest extends TestCase
{
    use RefreshDatabase;

    public function testMigrationCriaTabelaDeExercicios(): void
    {
        // Assert
        $this->assertTrue(
            Schema::hasTable('exercicios'),
        );

        $this->assertTrue(
            Schema::hasColumns(
                'exercicios',
                [
                    'id',
                    'nome',
                    'ativo',
                    'created_at',
                    'updated_at',
                ],
            ),
        );
    }
}
