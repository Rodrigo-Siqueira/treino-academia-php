<?php

declare(strict_types=1);

namespace App;

use InvalidArgumentException;
use LogicException;

class Exercicio
{
    private string $nome;
    private bool $ativo = true;

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

    public function estaAtivo(): bool
    {
        return $this->ativo;
    }

    public function inativar(): void
    {
        if (!$this->estaAtivo()) {
            throw new LogicException('Exercício já está inativo.');
        }
        $this->ativo = false;
    }

    public function ativar(): void
    {
        if ($this->estaAtivo()) {
            throw new LogicException('Exercício já está ativo.');
        }
        $this->ativo = true;
    }

    private function validarNome(string $nome): void
    {
        if ($nome === '') {
            throw new InvalidArgumentException('Exercício deve possuir um nome válido.');
        }
    }
}
