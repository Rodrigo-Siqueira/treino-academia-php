<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Exercicio;
use InvalidArgumentException;
use LogicException;
use PHPUnit\Framework\TestCase;

final class ExercicioTest extends TestCase
{
    public function testNormalizaOsEspacosExternosDoNome(): void
    {
        // Arrange
        $exercicio = new Exercicio('  Remada sentada  ');

        // Act
        $nome = $exercicio->nome();

        // Assert
        $this->assertSame('Remada sentada', $nome);
    }

    public function testNovoExercicioNasceAtivo(): void
    {
        $exercicio = new Exercicio('Agachamento');
        $ativo = $exercicio->estaAtivo();

        $this->assertSame(true, $ativo);
    }

    public function testExercicioAtivoPodeSerInativado(): void
    {
        $exercicio = new Exercicio('Agachamento Terra');
        $exercicio->inativar();
        $ativo = $exercicio->estaAtivo();

        $this->assertFalse($ativo);
    }

    public function testExercicioInativoPodeSerReativado(): void
    {
        $exercicio = new Exercicio('Agachamento Livre');
        $exercicio->inativar();
        $exercicio->ativar();
        $ativo = $exercicio->estaAtivo();

        $this->assertTrue($ativo);
    }

    public function testExercicioInativoNaoPodeSerInativadoNovamente(): void
    {
        $exercicio = new Exercicio('Agachamento Livre');
        $exercicio->inativar();

        $this->expectException(LogicException::class);
        $exercicio->inativar();

    }

    public function testExercicioAtivoNaoPodeSerAtivadoNovamente(): void
    {
        $exercicio = new Exercicio('Agachamento');

        $this->expectException(LogicException::class);
        $exercicio->ativar();
    }

    public function testExercicioNaoPodeSerCriadoComNomeVazio(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Exercicio('');
    }

    public function testExercicioNaoPodeSerCriadoComNomeContendoApenasEspacos(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Exercicio('  ');
    }
}
