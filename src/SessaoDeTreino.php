<?php

declare(strict_types=1);

namespace App;

use DateTimeImmutable;
use InvalidArgumentException;
use LogicException;

class SessaoDeTreino
{
    private DateTimeImmutable $inicio;
    private ?DateTimeImmutable $termino = null;
    private EstadoDaSessao $estado = EstadoDaSessao::EM_ANDAMENTO;

    /** @var list<SerieRealizada> */
    private array $series = [];

    public function __construct(DateTimeImmutable $inicio)
    {
        $this->inicio = $inicio;
    }

    public function cancelar(
        DateTimeImmutable $momentoDoCancelamento,
    ): void {
        $this->validarSeEstaEmAndamento();
        $this->validarMomentoDeEncerramento($momentoDoCancelamento);
        $this->termino = $momentoDoCancelamento;
        $this->estado = EstadoDaSessao::CANCELADA;
    }

    public function adicionarSerie(SerieRealizada $serie): void
    {
        $this->validarSeEstaEmAndamento();
        $this->series[] = $serie;
    }

    public function volumeTotal(): float
    {
        $volumeTotal = 0.0;
        foreach ($this->series as $serie) {

            $volumeTotal += $serie->volume();
        }

        return $volumeTotal;
    }

    public function duracaoEmMinutos(): int
    {
        if ($this->termino === null) {
            throw new LogicException(
                'A duração só pode ser consultada após o encerramento da sessão.',
            );
        }
        $intervalo = $this->inicio->diff($this->termino);

        return (
            ($intervalo->days * 24) + ($intervalo->h * 60) + $intervalo->i
        );
    }

    public function concluir(DateTimeImmutable $termino): void
    {
        $this->validarSeEstaEmAndamento();
        $this->validarMomentoDeEncerramento($termino);
        $this->termino = $termino;
        $this->estado = EstadoDaSessao::CONCLUIDA;
    }

    public function quantidadeDeSeries(): int
    {
        return count($this->series);
    }

    public function inicio(): DateTimeImmutable
    {
        return $this->inicio;
    }
    public function termino(): ?DateTimeImmutable
    {
        return $this->termino;
    }
    public function estado(): EstadoDaSessao
    {
        return $this->estado;
    }

    private function validarSeEstaEmAndamento(): void
    {
        if ($this->estado !== EstadoDaSessao::EM_ANDAMENTO) {
            throw new SessaoEncerrada(
                'A sessão não está em andamento.',
            );
        }
    }

    private function validarMomentoDeEncerramento(
        DateTimeImmutable $momento,
    ): void {
        if ($momento <= $this->inicio) {
            throw new InvalidArgumentException(
                'O encerramento deve ser posterior ao início.',
            );
        }
    }
}
