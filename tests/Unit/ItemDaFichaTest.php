<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Exercicio;
use App\ItemDaFicha;
use App\SerieRealizada;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class ItemDaFichaTest extends TestCase
{
    public function testCriaItemComPlanejamentoValido(): void
    {
        // Cenário
        $exercicio = new Exercicio('Supino com halteres');

        // Ação
        $item = new ItemDaFicha($exercicio, 3, 8, 12);

        // Assert
        $this->assertSame(
            $exercicio,
            $item->exercicio(),
        );

        $this->assertSame(
            3,
            $item->seriesPlanejadas(),
        );

        $this->assertSame(
            8,
            $item->repeticoesMinimas(),
        );

        $this->assertSame(
            12,
            $item->repeticoesMaximas(),
        );
    }

    public function testItemPodePlanejarUmaSerie(): void
    {
        // Cenário
        $exercicio = new Exercicio('Supino com halteres');

        // Ação
        $item = new ItemDaFicha($exercicio, 1, 8, 12);

        // Assert
        $this->assertSame(
            1,
            $item->seriesPlanejadas(),
        );
    }

    public function testItemNaoPodePlanejarZeroSeries(): void
    {
        $exercicio = new Exercicio('Supino com halteres');

        $this->expectException(
            InvalidArgumentException::class,
        );

        new ItemDaFicha(
            $exercicio,
            0,
            8,
            12,
        );
    }

    public function testItemPodePlanejarUmaRepeticaoMinima(): void
    {
        $exercicio = new Exercicio('Supino com halteres');

        $item = new ItemDaFicha(
            $exercicio,
            3,
            1,
            12,
        );

        $this->assertSame(
            1,
            $item->repeticoesMinimas(),
        );
    }

    public function testItemNaoPodePlanejarZeroRepeticoesMinimas(): void
    {
        $exercicio = new Exercicio('Supino com halteres');

        $this->expectException(
            InvalidArgumentException::class,
        );

        new ItemDaFicha(
            $exercicio,
            3,
            0,
            12,
        );
    }

    public function testItemPodePlanejarQuantidadeExataDeRepeticoes(): void
    {
        $exercicio = new Exercicio('Remada sentada');

        $item = new ItemDaFicha(
            $exercicio,
            3,
            10,
            10,
        );

        $this->assertSame(
            10,
            $item->repeticoesMinimas(),
        );

        $this->assertSame(
            10,
            $item->repeticoesMaximas(),
        );
    }

    public function testItemNaoPodeTerRepeticoesMaximasMenoresQueAsMinimas(): void
    {
        $exercicio = new Exercicio('Remada sentada');

        $this->expectException(
            InvalidArgumentException::class,
        );

        new ItemDaFicha(
            $exercicio,
            3,
            12,
            8,
        );
    }
}
