<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Exercicio;
use App\FichaDeTreino;
use App\ItemDaFicha;
use InvalidArgumentException;
use LogicException;
use PHPUnit\Framework\TestCase;

final class FichaDeTreinoTest extends TestCase
{
    public function testNovaFichaNormalizaONomeENasceSemItens(): void
    {
        $ficha = new FichaDeTreino('  Treino A  ');

        $this->assertSame('Treino A', $ficha->nome());
        $this->assertSame(0, $ficha->quantidadeDeItens());
    }

    public function testFichaNaoPodeSerCriadaComNomeVazio(): void
    {
        $this->expectException(
            InvalidArgumentException::class,
        );

        new FichaDeTreino('');
    }

    public function testFichaNaoPodeSerCriadaComNomeContendoApenasEspacos(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new FichaDeTreino('        ');
    }

    public function testFichaPodeInclirExerciciosAtivos(): void
    {
        $exercicio = new Exercicio('Remada sentada');

        $item = new ItemDaFicha($exercicio, 3, 8, 12);

        $ficha = new FichaDeTreino('Treino A');

        $ficha->adicionarItem($item);

        $quantidadeItens = $ficha->quantidadeDeItens();
        $this->assertSame(1, $quantidadeItens);
    }

    public function testFichaNaoPodeIncluirExerciciosInativos(): void
    {
        $exercicio = new Exercicio('Remada sentada');

        $item = new ItemDaFicha($exercicio, 3, 8, 12);

        $exercicio->inativar();

        $ficha = new FichaDeTreino('Treino A');

        $this->expectException(LogicException::class);

        $ficha->adicionarItem($item);
    }

    public function testFichaNaoPodeReceberOMesmoExercicioDuasVezes(): void
    {
        $exercicio = new Exercicio('Remada sentada');
        $primeiroItem = new ItemDaFicha($exercicio, 3, 8, 12);
        $segundoItem = new ItemDaFicha($exercicio, 4, 8, 12);

        $ficha = new FichaDeTreino('Treino A');
        $ficha->adicionarItem($primeiroItem);

        $this->expectException(LogicException::class);

        $ficha->adicionarItem($segundoItem);
    }

    public function testInclusaoDuplicadaNaoAlteraAFicha(): void
    {
        $exercicio = new Exercicio('Remada sentada');
        $primeiroItem = new ItemDaFicha($exercicio, 3, 8, 12);
        $segundoItem = new ItemDaFicha($exercicio, 4, 8, 12);

        $ficha = new FichaDeTreino('Treino A');
        $ficha->adicionarItem($primeiroItem);

        try {
            $ficha->adicionarItem($segundoItem);
            $this->fail('Era esperada uma LogicException.');
        } catch (LogicException $th) {
            // A exceção é esperada neste cenário.
        }

        $this->assertSame(1, $ficha->quantidadeDeItens());
    }

    public function testInativarExercicioNaoRemoveItemJaAdicionado(): void
    {
        // Arrange
        $exercicio = new Exercicio('Remada sentada');

        $item = new ItemDaFicha($exercicio, 3, 8, 12,);

        $ficha = new FichaDeTreino('Treino A');
        $ficha->adicionarItem($item);

        // Act
        $exercicio->inativar();

        // Assert
        $this->assertSame(1, $ficha->quantidadeDeItens(),);
    }

    public function testFichaCalculaTotalDeSeriesPlanejadas(): void
    {
        // Preparação
        $primeiroExercicio = new Exercicio('Supino com halteres');
        $segundoExercicio = new Exercicio('Remada sentada');
        $terceiroExercicio = new Exercicio('Elevação lateral');

        $primeiroItem = new ItemDaFicha($primeiroExercicio, 3, 8, 12,);
        $segundoItem = new ItemDaFicha($segundoExercicio, 4, 8, 12,);
        $terceiroItem = new ItemDaFicha($terceiroExercicio, 2, 8, 12,);

        $ficha = new FichaDeTreino('Treino A');
        $ficha->adicionarItem($primeiroItem);
        $ficha->adicionarItem($segundoItem);
        $ficha->adicionarItem($terceiroItem);

        // Ação
        $totalDeSeriesPlanejadas = $ficha->quantidadeTotalDeSeriesPlanejadas();

        // Assert
        $this->assertSame(9, $totalDeSeriesPlanejadas,);
    }

    public function testFichaVaziaPossuiZeroSeriesPlanejadas(): void
    {
        // Preparação
        $ficha = new FichaDeTreino('  Treino A  ');

        // Ação
        $totalDeSeriesPlanejadas = $ficha->quantidadeTotalDeSeriesPlanejadas();

        // Assert
        $this->assertSame(0, $totalDeSeriesPlanejadas);
    }

    public function testTentativaDeInclusaoDuplicadaPreservaOTotalDeSeries(): void
    {
        // Preparação
        $exercicio = new Exercicio('Supino com halteres');

        $primeiroItem = new ItemDaFicha($exercicio, 3, 8, 12,);
        $segundoItem = new ItemDaFicha($exercicio, 4, 8, 12,);

        $ficha = new FichaDeTreino('Treino B');
        $ficha->adicionarItem($primeiroItem);

        // Ação
        try {
            $ficha->adicionarItem($segundoItem);
            $this->fail('Deve lançar uma LogicException.');
        } catch (LogicException) {
            // A exceção é esperada neste cenário.
        }

        // Assert
        $this->assertSame(3, $ficha->quantidadeTotalDeSeriesPlanejadas(),);
    }

    public function testFichaVaziaPossuiZeroExerciciosPlanejados(): void
    {
        // Preparação
        $ficha = new FichaDeTreino('  Treino A  ');

        // Assert
        $this->assertSame(0, $ficha->quantidadeDeExerciciosPlanejados());
    }

    public function testeFichaPossuiTresExerciciosPlanejados(): void
    {
        // Preparação
        $primeiroExercicio = new Exercicio('Supino com halteres');
        $segundoExercicio = new Exercicio('Remada sentada');
        $terceiroExercicio = new Exercicio('Elevação lateral');

        $primeiroItem = new ItemDaFicha($primeiroExercicio, 3, 8, 12,);
        $segundoItem = new ItemDaFicha($segundoExercicio, 4, 8, 12,);
        $terceiroItem = new ItemDaFicha($terceiroExercicio, 2, 8, 12,);

        $ficha = new FichaDeTreino('Treino A');
        $ficha->adicionarItem($primeiroItem);
        $ficha->adicionarItem($segundoItem);
        $ficha->adicionarItem($terceiroItem);

        // Ação
        $totalDeExerciciosPlanejados = $ficha->quantidadeDeExerciciosPlanejados();

        // Assert
        $this->assertSame(3, $totalDeExerciciosPlanejados);
    }
}
