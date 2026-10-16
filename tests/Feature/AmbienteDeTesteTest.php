<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

final class AmbienteDeTesteTest extends TestCase
{
    public function testUtilizaBancoExclusivoDeTestes(): void
    {
        // Assert
        $this->assertSame(
            'testing',
            app()->environment(),
        );

        $this->assertSame(
            'treino_academia_test',
            config('database.connections.pgsql.database'),
        );
    }
}
