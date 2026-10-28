<?php

declare(strict_types=1);

namespace App;

use InvalidArgumentException;
use LogicException;

class Exercicio
{
    private ?int $id = null;
    private string $nome;
    private bool $ativo = true;

    public function __construct(string $nome)
    {
        $nome = trim($nome);
        $this->validarNome($nome);
        $this->nome = $nome;
    }

    public static function reconstituir(int $id, string $nome, bool $ativo): self
    {
        if ($id <= 0) {
            throw new InvalidArgumentException(
                'Exercício deve possuir um identificador válido.',
            );
        }

        $exercicio = new self($nome);
        $exercicio->id = $id;
        $exercicio->ativo = $ativo;

        return $exercicio;
    }

    public function id(): ?int
    {
        return $this->id;
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
