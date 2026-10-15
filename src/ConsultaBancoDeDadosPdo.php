<?php

declare(strict_types=1);

namespace App;

use PDO;
use RuntimeException;

final class ConsultaBancoDeDadosPdo implements ConsultaBancoDeDados
{
    public function __construct(
        private PDO $pdo,
    ) {
    }

    public function nomeDoBanco(): string
    {
        $resultado = $this->pdo->query(
            'SELECT current_database()',
        );

        if ($resultado === false) {
            throw new RuntimeException(
                'Não foi possível consultar o banco atual.',
            );
        }

        $nomeDoBanco = $resultado->fetchColumn();

        if (!is_string($nomeDoBanco)) {
            throw new RuntimeException(
                'O banco atual não retornou um nome válido.',
            );
        }

        return $nomeDoBanco;
    }
}
