<?php

declare(strict_types=1);

namespace App;

final class RespostaHttp
{
    /**
     * @param array<string, string> $cabecalhos
     */
    public function __construct(
        private int $status,
        private array $cabecalhos,
        private string $corpo,
    ) {
    }

    public function status(): int
    {
        return $this->status;
    }

    /**
     * @return array<string, string>
     */
    public function cabecalhos(): array
    {
        return $this->cabecalhos;
    }

    public function corpo(): string
    {
        return $this->corpo;
    }
}
