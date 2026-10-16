<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\CriarExercicioRequest;
use App\Models\ExercicioModel;
use Illuminate\Http\JsonResponse;

final class ExercicioController extends Controller
{
    public function store(
        CriarExercicioRequest $request,
    ): JsonResponse {
        $nome = $request->validated('nome');

        if (!is_string($nome)) {
            abort(500);
        }

        $exercicio = ExercicioModel::create([
            'nome' => $nome,
            'ativo' => true,
        ]);

        return response()->json(
            [
                'id' => $exercicio->id,
                'nome' => $exercicio->nome,
                'ativo' => $exercicio->ativo,
            ],
            201,
        );
    }

    public function index(): JsonResponse
    {
        $exercicios = ExercicioModel::query()
            ->orderBy('id')
            ->get([
                'id',
                'nome',
                'ativo',
            ]);

        return response()->json(
            $exercicios,
        );
    }
}
