<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Exercicio;
use App\SerieRealizada;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class SerieRealizadaTest extends TestCase
{
    public function testCalculaVolumeDaSerie(): void
    {
        // Arrange
        $exercicio = new Exercicio('Supino com halteres');

        $serie = new SerieRealizada(
            $exercicio,
            20.0,
            10,
        );

        // Act
        $volume = $serie->volume();

        // Assert
        $this->assertSame(200.0, $volume);
    }

    public function testSeriePodeSerRealizadaComCargaZero(): void
    {
        // Arrange
        $exercicio = new Exercicio('Flexão de braços');

        $serie = new SerieRealizada(
            $exercicio,
            0.0,
            15,
        );

        // Act
        $carga = $serie->carga();
        $volume = $serie->volume();

        // Assert
        $this->assertSame(0.0, $carga);
        $this->assertSame(0.0, $volume);
    }

    public function testSerieNaoPodeSerRealizadaComCargaNegativa(): void
    {
        $exercicio = new Exercicio('Supino com halteres');
        $this->expectException(InvalidArgumentException::class);

        new SerieRealizada(
            $exercicio,
            -5.0,
            10,
        );
    }

    public function testSeriePodeSerRealizadaComUmaRepeticao(): void
    {
        $exercicio = new Exercicio('Flexão de braços');

        $serie = new SerieRealizada(
            $exercicio,
            20.0,
            1,
        );

        $repeticoes = $serie->repeticoes();
        $volume = $serie->volume();

        $this->assertSame(1, $repeticoes);
        $this->assertSame(20.0, $volume);
    }

    public function testSerieNaoPodeSerRealizadaComZeroRepeticoes(): void
    {
        $exercicio = new Exercicio('Flexão de braços');
        $this->expectException(InvalidArgumentException::class);

        new SerieRealizada(
            $exercicio,
            20.0,
            0,
        );
    }

    public function testSerieNaoPodeSerRealizadaComRepeticoesNegativas(): void
    {
        $exercicio = new Exercicio('Cadeira Flexora');
        $this->expectException(InvalidArgumentException::class);

        new SerieRealizada(
            $exercicio,
            10.0,
            -1,
        );
    }

    public function testSerieMantemOExercicioInformado(): void
    {
        // Arrange
        $exercicio = new Exercicio('Remada sentada');

        $serie = new SerieRealizada(
            $exercicio,
            30.0,
            12,
        );

        // Act
        $exercicioObtido = $serie->exercicio();

        // Assert
        $this->assertSame($exercicio, $exercicioObtido);
    }
}
