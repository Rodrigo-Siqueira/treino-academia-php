<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\EstadoDaSessao;
use PHPUnit\Framework\TestCase;

final class EstadoDaSessaoTest extends TestCase
{
    public function testEstadoDaSessaoEmAndamento(): void
    {
        // Arrange
        $estado = EstadoDaSessao::EM_ANDAMENTO;

        // Assert
        $this->assertSame(
            EstadoDaSessao::EM_ANDAMENTO,
            $estado,
        );
    }
}
