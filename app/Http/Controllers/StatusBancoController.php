<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\ConsultaBancoDeDados;
use Illuminate\Http\JsonResponse;

final class StatusBancoController extends Controller
{
    public function __construct(
        private ConsultaBancoDeDados $consultaBancoDeDados,
    ) {}

    public function show(): JsonResponse
    {
        return response()->json([
            'banco' => $this
                ->consultaBancoDeDados
                ->nomeDoBanco(),
        ]);
    }
}
