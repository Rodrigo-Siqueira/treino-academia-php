<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Application\Exercicios\InativarExercicio;
use App\Http\Requests\CriarExercicioRequest;
use App\Http\Resources\ExercicioResource;
use App\Models\ExercicioModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use LogicException;

final class ExercicioController extends Controller
{
    public function __construct(
        private InativarExercicio $inativarExercicio,
    ) {}

    public function store(CriarExercicioRequest $request): JsonResponse
    {
        $nome = $request->validated('nome');

        if (!is_string($nome)) {
            abort(500);
        }

        $exercicio = ExercicioModel::create([
            'nome' => $nome,
            'ativo' => true,
        ]);

        return (new ExercicioResource($exercicio))
            ->response()
            ->setStatusCode(201);
    }

    public function index(): AnonymousResourceCollection
    {
        $exercicios = ExercicioModel::query()
            ->orderBy('id')
            ->get();

        return ExercicioResource::collection($exercicios);
    }

    public function show(ExercicioModel $exercicio): ExercicioResource
    {
        return new ExercicioResource($exercicio);
    }

    public function inativar(
        ExercicioModel $exercicio,
    ): ExercicioResource|JsonResponse {
        try {
            $exercicio = $this->inativarExercicio->executar($exercicio);
        } catch (LogicException $erro) {
            return response()->json(
                [
                    'erro' => $erro->getMessage(),
                ],
                409,
            );
        }

        return new ExercicioResource($exercicio);
    }
}
