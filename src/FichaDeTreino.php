<?php

declare(strict_types=1);

namespace App;

use InvalidArgumentException;
use LogicException;

class FichaDeTreino
{
    private string $nome;

    /** @var list<ItemDaFicha> */
    private array $itens = [];

    public function __construct(string $nome)
    {
        $nome = trim($nome);
        $this->validarNome($nome);
        $this->nome = $nome;
    }

    public function nome(): string
    {
        return $this->nome;
    }

    public function quantidadeDeItens(): int
    {
        return count($this->itens);
    }
    
    public function quantidadeDeExerciciosPlanejados(): int
    {
        return $this->quantidadeDeItens();
    }

    public function quantidadeTotalDeSeriesPlanejadas(): int
    {
        $totalDeSereis = 0;
        foreach ($this->itens as $item) {
            $totalDeSereis += $item->seriesPlanejadas();
        }

        return $totalDeSereis;
    }

    public function adicionarItem(ItemDaFicha $item): void
    {
        $this->validarItem($item);
        $this->itens[] = $item;
    }

    private function validarItem(ItemDaFicha $item): void
    {
        $this->validarSeExercicioEstaAtivo($item->exercicio());
        $this->validarSeExercicioNaoFoiAdicionado($item);
    }

    private function validarNome(string $nome): void
    {
        if ($nome === '') {
            throw new InvalidArgumentException(
                'Ficha de treino deve possuir um nome válido.',
            );
        }
    }

    private function validarSeExercicioEstaAtivo(Exercicio $exercicio): void
    {
        if (!$exercicio->estaAtivo()) {
            throw new LogicException(
                'Não é possível adicionar um exercício inativo à ficha.',
            );
        }
    }

    private function validarSeExercicioNaoFoiAdicionado(ItemDaFicha $novoItem): void
    {
        foreach ($this->itens as $itemExistente) {
            if ($novoItem->exercicio() === $itemExistente->exercicio()) {
                throw new LogicException(
                    'O exercício já foi adicionado à ficha.',
                );
            }
        }
    }
}
