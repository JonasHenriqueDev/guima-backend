<?php

use App\Http\Controllers\AlunoController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\MeController;
use App\Http\Controllers\ModuloController;
use App\Http\Controllers\ProfessorController;
use App\Http\Controllers\SubmoduloController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VideoAulaController;

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
        'success' => true
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
    // Rotas para Professores e Usuários
    Route::apiResource('professores', ProfessorController::class);
    Route::apiResource('users', UserController::class);

    // Rotas para Módulos, Submódulos e Videoaulas
    Route::prefix('modulos')->group(function () {
        Route::apiResource('/', ModuloController::class);

        // Rotas para Submódulos e Videoaulas usando parâmetros de URL
        Route::get('{moduloId}/submodulos', [SubmoduloController::class, 'index']);
        Route::get('{moduloId}/submodulos/{submoduloId}', [SubmoduloController::class, 'show']);
        Route::apiResource('{moduloId}/submodulos/{submoduloId}/video_aulas', VideoAulaController::class);
    });
});


// Rotas acessíveis por alunos e professores
Route::middleware(['auth:sanctum', 'ability:aluno,professor'])->group(function () {
    Route::get('/me', [MeController::class, 'me']);

    // Rotas relacionadas a módulos
    Route::prefix('modulos')->group(function () {
        Route::get('/', [ModuloController::class, 'index']);
        Route::get('/{id}', [ModuloController::class, 'show']);

        Route::prefix('{moduloId}/video_aulas')->group(function () {
            Route::get('/', [VideoAulaController::class, 'index']);
            Route::get('/{id}', [VideoAulaController::class, 'show']);
        });
    });
});
