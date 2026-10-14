<?php

declare(strict_types=1);

namespace App;

final class RequisicaoHttp
{
    private string $metodo;

    /**
     * @param array<string, string> $parametrosDeConsulta
     */
    public function __construct(
        string $metodo,
        private string $caminho,
        private array $parametrosDeConsulta = [],
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

    public function parametroDeConsulta(
        string $nome,
    ): ?string {
        return $this->parametrosDeConsulta[$nome] ?? null;
    }
}
