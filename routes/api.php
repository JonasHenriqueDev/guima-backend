<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\MeController;
use App\Http\Controllers\ModuloController;
use App\Http\Controllers\ProfessorController;
use App\Http\Controllers\SubmoduloController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AulaController;
use App\Http\Controllers\ImageController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return response()->json([
        'ping' => 'pong',
    ]);
});

// Rotas para autenticação
Route::prefix('auth')->group(function () {
    Route::post('login', [LoginController::class, 'login']);
    Route::post('register', [UserController::class, 'store']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('reset_password', [LoginController::class, 'resetPassword']);
        Route::post('logout', [LoginController::class, 'logout']);
    });
});

// Rotas acessíveis apenas por professores
Route::middleware(['auth:sanctum', 'ability:professor'])->group(function () {
    Route::apiResource('professores', ProfessorController::class);
    Route::apiResource('users', UserController::class);

    // Rotas para Módulos, Submódulos e aulas
    Route::prefix('modulos')->group(function () {
        Route::post('/', [ModuloController::class, 'store']);
        Route::put('{id}', [ModuloController::class, 'update']);
        Route::patch('{id}', [ModuloController::class, 'update']);
        Route::delete('{id}', [ModuloController::class, 'destroy']);


        Route::apiResource('{moduloId}/submodulos', SubmoduloController::class)->except(['show']);
        Route::get('{moduloId}/submodulos/{submoduloId}', [SubmoduloController::class, 'show']);
        Route::apiResource('{moduloId}/submodulos/{submoduloId}/aulas', AulaController::class);
    });
});


// Rotas acessíveis por qualquer usuario logado
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [MeController::class, 'me']);
    Route::get('/image', [ImageController::class, 'show']);

    // Rotas relacionadas a módulos, submodulos e aulas
    Route::prefix('modulos')->group(function () {
        Route::get('/', [ModuloController::class, 'index']);
        Route::get('/{id}', [ModuloController::class, 'show']);

        Route::get('{moduloId}/submodulos', [SubmoduloController::class, 'index']);
        Route::get('{moduloId}/submodulos/{submoduloId}', [SubmoduloController::class, 'show']);

        Route::prefix('{moduloId}/submodulos/{submoduloId}/aulas')->group(function () {
            Route::get('/', [AulaController::class, 'index']);
            Route::get('/{id}', [AulaController::class, 'show']);
        });
    });
});
