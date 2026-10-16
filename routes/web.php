<?php

use App\Http\Controllers\StatusBancoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/status', function () {
    return response()->json([
        'status' => 'ok',
        'php' => PHP_VERSION,
    ]);
});

Route::get('/saudacao', function (Request $request) {
    $nome = $request->query('nome');

    if (!is_string($nome) || trim($nome) === '') {
        return response()->json(
            [
                'erro' => 'O parâmetro nome é obrigatório.',
            ],
            400,
        );
    }

    $nomeSeguro = e(trim($nome));

    return response(
        sprintf(
            '<h1>Olá, %s!</h1>',
            $nomeSeguro,
        ),
        200,
        [
            'Content-Type' => 'text/html; charset=UTF-8',
        ],
    );
});

Route::get('/banco', [StatusBancoController::class, 'show']);