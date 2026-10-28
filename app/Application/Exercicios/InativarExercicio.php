<?php

declare(strict_types=1);

namespace App\Application\Exercicios;

use App\Exercicio;
use App\Models\ExercicioModel;

final class InativarExercicio
{
    public function executar(ExercicioModel $modelo): ExercicioModel
    {
        $exercicio = Exercicio::reconstituir(
            id: $modelo->id,
            nome: $modelo->nome,
            ativo: $modelo->ativo,
        );

        $exercicio->inativar();

        $modelo->ativo = $exercicio->estaAtivo();
        $modelo->save();

        return $modelo;
    }
}
