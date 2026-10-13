<?php

declare(strict_types=1);

namespace App;

use InvalidArgumentException;

class ItemDaFicha
{
    private Exercicio $exercicio;
    private int $seriesPlanejadas;
    private int $repeticoesMinimas;
    private int $repeticoesMaximas;

    public function __construct(
        Exercicio $exercicio,
        int $seriesPlanejadas,
        int $repeticoesMinimas,
        int $repeticoesMaximas,
    ) {
        $this->validarSeriesPlanejadas($seriesPlanejadas);
        $this->validarRepeticoesMinimas($repeticoesMinimas);
        $this->validarFaixaDeRepeticoes(
            $repeticoesMinimas,
            $repeticoesMaximas,
        );

        $this->exercicio = $exercicio;
        $this->seriesPlanejadas = $seriesPlanejadas;
        $this->repeticoesMinimas = $repeticoesMinimas;
        $this->repeticoesMaximas = $repeticoesMaximas;
    }

    public function exercicio(): Exercicio
    {
        return $this->exercicio;
    }

    public function seriesPlanejadas(): int
    {
        return $this->seriesPlanejadas;
    }

    public function repeticoesMinimas(): int
    {
        return $this->repeticoesMinimas;
    }

    public function repeticoesMaximas(): int
    {
        return $this->repeticoesMaximas;
    }

    private function validarSeriesPlanejadas(int $seriesPlanejadas): void
    {
        if ($seriesPlanejadas < 1) {
            throw new InvalidArgumentException(
                'A quantidade de séries planejadas deve ser maior que zero.',
            );
        }
    }

    private function validarRepeticoesMinimas(int $repeticoesMinimas): void
    {
        if ($repeticoesMinimas < 1) {
            throw new InvalidArgumentException(
                'A quantidade mínima de repetições deve ser maior que zero.',
            );
        }
    }

    private function validarFaixaDeRepeticoes(
        int $repeticoesMinimas,
        int $repeticoesMaximas,
    ): void {
        if ($repeticoesMaximas < $repeticoesMinimas) {
            throw new InvalidArgumentException(
                'A quantidade máxima de repetições não pode ser menor que a mínima.',
            );
        }
    }
}
