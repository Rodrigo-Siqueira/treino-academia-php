<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\EstadoDaSessao;
use App\Exercicio;
use App\SerieRealizada;
use App\SessaoDeTreino;
use App\SessaoEncerrada;
use DateTimeImmutable;
use InvalidArgumentException;
use LogicException;
use PHPUnit\Framework\TestCase;

final class SessaoDeTreinoTest extends TestCase
{
    public function testNovaSessaoNasceEmAndamentoSemTermino(): void
    {
        // Arrange
        $inicio = new DateTimeImmutable(
            '2026-10-10 14:00:00',
        );

        // Act
        $sessao = new SessaoDeTreino($inicio);

        // Assert
        $this->assertSame($inicio, $sessao->inicio());
        $this->assertNull($sessao->termino());

        $this->assertSame(
            EstadoDaSessao::EM_ANDAMENTO,
            $sessao->estado(),
        );
    }

    public function testSessaoEmAndamentoPodeSerConcluida(): void
    {
        // Arrange
        $inicio = new DateTimeImmutable(
            '2026-10-10 14:00:00',
        );
        $termino = new DateTimeImmutable(
            '2026-10-10 15:30:00',
        );

        // Act
        $sessao = new SessaoDeTreino($inicio);
        $sessao->concluir($termino);

        // Assert
        $this->assertSame(
            $termino,
            $sessao->termino(),
        );
        $this->assertSame(
            EstadoDaSessao::CONCLUIDA,
            $sessao->estado(),
        );
    }

    public function testSessaoNaoPodeSerConcluidaNoMesmoInstanteDoInicio(): void
    {
        // Arrange
        $inicio = new DateTimeImmutable(
            '2026-10-10 14:00:00',
        );

        $sessao = new SessaoDeTreino($inicio);

        // Expectativa
        $this->expectException(
            InvalidArgumentException::class,
        );

        // Act
        $sessao->concluir($inicio);
    }

    public function testSessaoNaoPodeSerConcluidaAntesDoInicio(): void
    {
        // Arrange
        $inicio = new DateTimeImmutable(
            '2026-10-10 14:00:00',
        );

        $sessao = new SessaoDeTreino($inicio);

        // Expectativa
        $this->expectException(
            InvalidArgumentException::class,
        );

        // Act
        $sessao->concluir(
            new DateTimeImmutable('2026-10-10 13:59:59'),
        );
    }

    public function testSessaoConcluidaNaoPodeSerConcluidaNovamente(): void
    {
        $inicio = new DateTimeImmutable(
            '2026-10-10 14:00:00',
        );

        $sessao = new SessaoDeTreino($inicio);

        $sessao->concluir(
            new DateTimeImmutable('2026-10-10 15:00:00'),
        );

        $this->expectException(SessaoEncerrada::class);

        $sessao->concluir(
            new DateTimeImmutable('2026-10-10 16:00:00'),
        );
    }

    public function testSessaoEmAndamentoPodeSerCancelada(): void
    {
        // Arrange
        $inicio = new DateTimeImmutable(
            '2026-10-10 14:00:00',
        );
        $momentoDoCancelamento = new DateTimeImmutable(
            '2026-10-10 14:30:00',
        );

        $sessao = new SessaoDeTreino($inicio);

        // Act
        $sessao->cancelar($momentoDoCancelamento);

        // Assert
        $this->assertSame(
            $momentoDoCancelamento,
            $sessao->termino(),
        );
        $this->assertSame(
            EstadoDaSessao::CANCELADA,
            $sessao->estado(),
        );
    }

    public function testSessaoCanceladaNaoPodeSerCanceladaNovamente(): void
    {
        // Arrange
        $inicio = new DateTimeImmutable(
            '2026-10-10 14:00:00',
        );

        $sessao = new SessaoDeTreino($inicio);

        $sessao->cancelar(
            new DateTimeImmutable('2026-10-10 14:30:00'),
        );

        $this->expectException(SessaoEncerrada::class);

        // Act
        $sessao->cancelar(
            new DateTimeImmutable('2026-10-10 15:00:00'),
        );
    }
    public function testSessaoCanceladaNaoPodeSerConcluida(): void
    {
        // Arrange
        $inicio = new DateTimeImmutable(
            '2026-10-10 14:00:00',
        );

        $sessao = new SessaoDeTreino($inicio);

        $sessao->cancelar(
            new DateTimeImmutable('2026-10-10 14:30:00'),
        );

        // Expectativa
        $this->expectException(SessaoEncerrada::class);

        // Act
        $sessao->concluir(
            new DateTimeImmutable('2026-10-10 15:00:00'),
        );
    }

    public function testSessaoConcluidaNaoPodeSerCancelada(): void
    {
        // Arrange
        $inicio = new DateTimeImmutable(
            '2026-10-10 14:00:00',
        );

        $sessao = new SessaoDeTreino($inicio);

        $sessao->concluir(
            new DateTimeImmutable('2026-10-10 15:00:00'),
        );

        // Expectativa
        $this->expectException(SessaoEncerrada::class);

        // Act
        $sessao->cancelar(
            new DateTimeImmutable('2026-10-10 15:30:00'),
        );
    }

    public function testSessaoNaoPodeSerCanceladaNoMesmoInstanteDoInicio(): void
    {
        // Arrange
        $inicio = new DateTimeImmutable(
            '2026-10-10 14:00:00',
        );

        $sessao = new SessaoDeTreino($inicio);

        // Expectativa
        $this->expectException(
            InvalidArgumentException::class,
        );

        // Act
        $sessao->cancelar($inicio);
    }

    public function testSessaoNaoPodeSerCanceladaAntesDoInicio(): void
    {
        // Arrange
        $inicio = new DateTimeImmutable(
            '2026-10-10 14:00:00',
        );

        $sessao = new SessaoDeTreino($inicio);

        // Expectativa
        $this->expectException(
            InvalidArgumentException::class,
        );

        // Act
        $sessao->cancelar(
            new DateTimeImmutable('2026-10-10 13:59:59'),
        );
    }

    public function testSessaoEmAndamentoPodeReceberUmaSerie(): void
    {
        // Arrange
        $inicio = new DateTimeImmutable('2026-10-10 14:00:00');
        $sessao = new SessaoDeTreino($inicio);
        $exercicio = new Exercicio('Supino com halteres');

        $serie = new SerieRealizada($exercicio, 20.0, 10);

        // Act
        $sessao->adicionarSerie($serie);

        // Assert
        $this->assertSame(1, $sessao->quantidadeDeSeries());
    }

    public function testSessaoEmAndamentoPodeReceberVariasSeries(): void
    {
        // Arrange
        $inicio = new DateTimeImmutable('2026-10-10 14:00:00');
        $sessao = new SessaoDeTreino($inicio);
        $exercicio = new Exercicio('Supino com halteres');
        $primeiraSerie = new SerieRealizada($exercicio, 20.0, 10);
        $segundaSerie = new SerieRealizada($exercicio, 22.0, 8);

        // Act
        $sessao->adicionarSerie($primeiraSerie);
        $sessao->adicionarSerie($segundaSerie);

        // Assert
        $this->assertSame(2, $sessao->quantidadeDeSeries());
    }

    public function testSessaoConcluidaNaoPodeReceberSerie(): void
    {
        // Arrange
        $inicio = new DateTimeImmutable('2026-10-10 14:00:00');
        $sessao = new SessaoDeTreino($inicio);
        $sessao->concluir(new DateTimeImmutable('2026-10-10 15:00:00'));

        $exercicio = new Exercicio('Supino com halteres');
        $serie = new SerieRealizada($exercicio, 20.0, 10);

        // Espectativa
        $this->expectException(SessaoEncerrada::class);

        // Act
        $sessao->adicionarSerie($serie);
    }

    public function testSessaoCanceladaNaoPodeReceberSerie(): void
    {
        // Arrange
        $inicio = new DateTimeImmutable('2026-10-10 14:00:00');
        $sessao = new SessaoDeTreino($inicio);
        $sessao->cancelar(new DateTimeImmutable('2026-10-10 14:30:00'));

        $exercicio = new Exercicio('Supino com halteres');
        $serie = new SerieRealizada($exercicio, 20.0, 10);

        // Espectativa
        $this->expectException(SessaoEncerrada::class);

        // Act
        $sessao->adicionarSerie($serie);
    }

    public function testSessaoSemSeriesPossuiVolumeTotalZero(): void
    {
        // Arrange
        $inicio = new DateTimeImmutable('2026-10-10 14:00:00');
        $sessao = new SessaoDeTreino($inicio);

        // Act
        $volumeTotal = $sessao->volumeTotal();

        // Assert
        $this->assertSame(0.0, $volumeTotal);
    }

    public function testSessaoCalculaVolumeTotalDasSeries(): void
    {
        // Arrange
        $inicio = new DateTimeImmutable('2026-10-10 14:00:00');
        $sessao = new SessaoDeTreino($inicio);
        $exercicio = new Exercicio('Supino com halteres');
        $primeiraSerie = new SerieRealizada($exercicio, 20.0, 10);
        $segundaSerie = new SerieRealizada($exercicio, 22.0, 8);
        $sessao->adicionarSerie($primeiraSerie);
        $sessao->adicionarSerie($segundaSerie);

        // Act
        $volumeTotal = $sessao->volumeTotal();

        // Assert
        $this->assertSame(376.0, $volumeTotal);
    }

    public function testSessaoConcluidaCalculaDuracaoEmMinutos(): void
    {
        // Arrange
        $inicio = new DateTimeImmutable('2026-10-10 14:00:00');
        $termino = new DateTimeImmutable('2026-10-10 15:30:00');

        $sessao = new SessaoDeTreino($inicio);
        $sessao->concluir($termino);

        // Act
        $duracao = $sessao->duracaoEmMinutos();

        // Assert
        $this->assertSame(90, $duracao);
    }

    public function testSessaoCanceladaCalculaDuracaoAteOCancelamento(): void
    {
        // Arrange
        $inicio = new DateTimeImmutable('2026-10-10 14:00:00');
        $termino = new DateTimeImmutable('2026-10-10 14:45:00');

        $sessao = new SessaoDeTreino($inicio);
        $sessao->cancelar($termino);

        // Act
        $duracao = $sessao->duracaoEmMinutos();

        // Assert
        $this->assertSame(45, $duracao);
    }

    public function testSessaoEmAndamentoNaoPossuiDuracaoConsolidada(): void
    {
        // Arrange
        $inicio = new DateTimeImmutable('2026-10-10 14:00:00');
        $sessao = new SessaoDeTreino($inicio);

        // Espectativa
        $this->expectException(LogicException::class);

        // Act
        $sessao->duracaoEmMinutos();
    }

    public function testSessaoCalculaDuracaoQuandoAtravessaAMeiaNoite(): void
    {
        // Arrange
        $inicio = new DateTimeImmutable('2026-10-10 23:30:00');
        $termino = new DateTimeImmutable('2026-10-11 01:00:00');

        $sessao = new SessaoDeTreino($inicio);
        $sessao->concluir($termino);

        // Act
        $duracao = $sessao->duracaoEmMinutos();

        // Assert
        $this->assertSame(90, $duracao);
    }

    public function testConclusaoInvalidaNaoAlteraASessao(): void
    {
        // Preparação
        $inicio = new DateTimeImmutable('2026-10-10 14:00:00');
        $terminoInvalido = new DateTimeImmutable('2026-10-10 13:59:00');

        $sessao = new SessaoDeTreino($inicio);
        // Ação
        try {
            $sessao->concluir($terminoInvalido);
            $this->fail('Era esperada uma InvalidArgumentException.');
        } catch (InvalidArgumentException) {
            // A exceção é esperada neste cenário.
        }

        $this->assertSame(EstadoDaSessao::EM_ANDAMENTO, $sessao->estado());
        $this->assertNull($sessao->termino());
    }

    public function testCancelamentoInvalidoNaoAlteraSessaoConcluida(): void
    {
        // Preparação
        $inicio = new DateTimeImmutable('2026-10-10 14:00:00');
        $terminoOriginal = new DateTimeImmutable('2026-10-10 15:00:00');

        $sessao = new SessaoDeTreino($inicio);
        $sessao->concluir($terminoOriginal);

        // Ação
        try {
            $sessao->concluir(new DateTimeImmutable('2026-10-10 16:00:00'));
            $this->fail('Era esperada uma LogicException.');
        } catch (LogicException) {
            // A exceção é esperada neste cenário.
        }

        $this->assertSame(EstadoDaSessao::CONCLUIDA, $sessao->estado());
        $this->assertSame($terminoOriginal, $sessao->termino());
    }
}
