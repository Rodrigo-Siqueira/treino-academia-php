<?php

declare(strict_types=1);

namespace App;

final class RequisicaoHttp
{
    private string $metodo;

    public function __construct(
        string $metodo,
        private string $caminho,
    ) {
        $this->metodo = strtoupper($metodo);
    }

    public function metodo(): string
    {
        return $this->metodo;
    }

    public function caminho(): string
    {
        return $this->caminho;
    }
}
