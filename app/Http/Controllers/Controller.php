<?php

namespace App\Http\Controllers;

use App\Traits\HttpResponses;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *   version="1.0.0",
 *   title="GuimaAPI",
 *   description="API para gerenciamento de alunos, treino, dieta, aulas e avaliações físicas.",
 * )
 *
 * @OA\SecurityScheme(
 *     type="http",
 *     in="header",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     securityScheme="apiAuth",
 * )
 */
class Controller extends BaseController
{
    const NOT_FOUND_MSG = 'Aluno não encontrado!';
    const INTERNAL_SERVER_ERROR = 'Erro interno do servidor!';
    use AuthorizesRequests, ValidatesRequests, HttpResponses;
}
