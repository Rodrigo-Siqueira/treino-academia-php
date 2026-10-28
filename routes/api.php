<?php

declare(strict_types=1);

use App\Http\Controllers\ExercicioController;
use Illuminate\Support\Facades\Route;

Route::get('/exercicios', [ExercicioController::class, 'index']);

Route::post('/exercicios', [ExercicioController::class, 'store']);

Route::get('/exercicios/{exercicio}', [ExercicioController::class, 'show']);

Route::patch(
    '/exercicios/{exercicio}/inativacao',
    [ExercicioController::class, 'inativar'],
);