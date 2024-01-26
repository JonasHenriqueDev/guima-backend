<?php

use App\Http\Controllers\AlunoController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\MeController;
use App\Http\Controllers\ProfessorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

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

Route::prefix('auth')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('reset_password', [LoginController::class, 'resetPassword']);
        Route::post('logout', [LoginController::class, 'logout']);
    });
    Route::post('login', [LoginController::class, 'login']);
    Route::post('register', [UserController::class, 'store']);
});

Route::middleware(['auth:sanctum', 'ability:professor'])->group(function () {
    Route::get('/me', [MeController::class, 'me']);
    Route::apiResource('users', UserController::class);
    Route::apiResource('professores', ProfessorController::class);
    Route::apiResource('alunos', AlunoController::class);
});
