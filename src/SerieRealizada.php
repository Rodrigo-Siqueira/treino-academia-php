<?php

declare(strict_types=1);

namespace App;

use InvalidArgumentException;

class SerieRealizada
{
    private Exercicio $exercicio;
    private float $carga;
    private int $repeticoes;

    public function __construct(
        Exercicio $exercicio,
        float $carga,
        int $repeticoes,
    ) {
        $this->validarSerieRealizada($carga, $repeticoes);
        $this->exercicio = $exercicio;
        $this->carga = $carga;
        $this->repeticoes = $repeticoes;
    }

    private function validarSerieRealizada(float $carga, int $repeticoes): void
    {
        $this->validarCarga($carga);
        $this->validarRepeticoes($repeticoes);
    }

    private function validarCarga(float $carga): void
    {
        if ($carga < 0) {
            throw new InvalidArgumentException(
                'A carga não pode ser negativa.',
            );
        }
    }

    private function validarRepeticoes(int $repeticoes): void
    {
        if ($repeticoes < 1) {
            throw new InvalidArgumentException(
                'A quantidade de repetições deve ser maior que zero.',
            );
        }
    }

    public function exercicio(): Exercicio
    {
        return $this->exercicio;
    }

    public function carga(): float
    {
        return $this->carga;
    }

    public function repeticoes(): int
    {
        return $this->repeticoes;
    }

    public function volume(): float
    {
        return $this->carga * $this->repeticoes;
    }
}
