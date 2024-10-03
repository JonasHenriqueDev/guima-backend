<?php

use App\Http\Controllers\AlunoController;
use App\Http\Controllers\AnamneseController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\MeController;
use App\Http\Controllers\ModuloController;
use App\Http\Controllers\ProfessorController;
use App\Http\Controllers\SubmoduloController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AulaController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\FeedbackSettingController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\ProtocoloController;
use App\Models\Feedback;
use Illuminate\Support\Facades\DB;

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
        'name' => config('app.name'),
        'api' => 'v1',
        'status' => 'online',
        'docs' => config('app.url') . 'api/docs',
        'userAgent' => request()->userAgent(),
    ]);
});

Route::prefix('api/v1')->middleware('json.response')->group(function () {

    // Rotas para autenticação
    Route::prefix('auth')->group(function () {
        Route::post('login', [LoginController::class, 'login']);
        Route::post('register', [UserController::class, 'store']);

        Route::middleware('auth:sanctum')->group(function () {
            Route::post('reset-password', [LoginController::class, 'resetPassword']);
            Route::post('logout', [LoginController::class, 'logout']);
        });
    });

    // Rotas acessíveis apenas por professores
    Route::middleware(['auth:sanctum', 'ability:professor'])->group(function () {
        Route::apiResource('professores', ProfessorController::class);
        Route::apiResource('users', UserController::class);

        Route::prefix('alunos')->group(function () {
            Route::apiResource('/', AlunoController::class);
            Route::get('protocolos', [ProtocoloController::class, 'index']);

            Route::post('/{alunoId}/protocolos', [ProtocoloController::class, 'store']);
            Route::post('/{alunoId}/protocolos/reenviar', [ProtocoloController::class, 'resend']);
        });

        Route::apiResource('modulos', ModuloController::class);
        Route::apiResource('submodulos', SubmoduloController::class);
        Route::apiResource('aulas', AulaController::class);

        // Rotas para Feedback
        Route::prefix('feedbacks')->group(function () {
            Route::apiResource('/', FeedbackController::class);
            Route::post('/aprovar/{id}', [FeedbackController::class, 'aprovarFeedback']);
            Route::post('/reprovar/{id}', [FeedbackController::class, 'reprovarFeedback']);
            Route::get('/settings', [FeedbackSettingController::class, 'index']);
            Route::post('/settings', [FeedbackSettingController::class, 'store']);
        });

        // Rotas para Anamneses
        Route::prefix('anamnese')->group(function () {
            Route::get('/', [AnamneseController::class, 'index']);
            Route::post('/aprovar/{id}', [AnamneseController::class, 'aprovarAnamnese']);
            Route::post('/reprovar/{id}', [AnamneseController::class, 'reprovarAnamnese']);
        });

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

        // Rotas para Feedback
        Route::post('/feedbacks', [FeedbackController::class, 'store']);
        Route::patch('/feedbacks/{id}', [FeedbackController::class, 'update']);

        //Rotas para protocolo
        Route::prefix('protocolos')->group(function () {
            Route::get('/my', [ProtocoloController::class, 'showMyProtocolos']);
            Route::post('/aprovar/{id}', [ProtocoloController::class, 'aprovar']);
            Route::post('/reprovar/{id}', [ProtocoloController::class, 'reprovar']);
            Route::get('/download/{id}', [ProtocoloController::class, 'download']);
        });

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

    // Rotas para novos usuários que farão anamnese
    Route::prefix('anamnese')->group(function () {
        Route::get('/{cpf}', [AnamneseController::class, 'show']);
        Route::post('/', [AnamneseController::class, 'store']);
        Route::patch('/{cpf}', [AnamneseController::class, 'update']);
    });
});
