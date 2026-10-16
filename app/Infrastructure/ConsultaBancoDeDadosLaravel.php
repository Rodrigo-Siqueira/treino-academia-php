<?php

declare(strict_types=1);

namespace App\Infrastructure;

use App\ConsultaBancoDeDados;
use Illuminate\Database\ConnectionInterface;
use RuntimeException;

final class ConsultaBancoDeDadosLaravel implements ConsultaBancoDeDados
{
    public function __construct(
        private ConnectionInterface $conexao,
    ) {}

    public function nomeDoBanco(): string
    {
        $resultado = $this->conexao->selectOne(
            'SELECT current_database() AS nome',
        );

        if (
            !is_object($resultado)
            || !property_exists($resultado, 'nome')
        ) {
            throw new RuntimeException(
                'O banco atual não retornou um nome válido.',
            );
        }

        $nomeDoBanco = $resultado->nome;

        if (!is_string($nomeDoBanco)) {
            throw new RuntimeException(
                'O banco atual não retornou um nome textual.',
            );
        }

        return $nomeDoBanco;
    }
}
