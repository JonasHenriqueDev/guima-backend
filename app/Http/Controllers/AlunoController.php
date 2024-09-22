<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAlunoRequest;
use App\Http\Requests\UpdateAlunoRequest;
use App\Http\Resources\AlunoResource;
use App\Http\Resources\AlunosResource;
use App\Models\Aluno;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AlunoController extends Controller
{
    const NOT_FOUND_MSG = 'Aluno não encontrado!';
    const INTERNAL_SERVER_ERROR = 'Erro interno do servidor!';

    public function __construct(
        protected Aluno $repository,
    ) {
        // $this->middleware('auth:sanctum')->except(['store']);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/alunos",
     *     summary="Listar todos os alunos",
     *     tags={"Alunos"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(response="200", description="Retorna a lista de alunos"),
     *     security={
     *          { "apiAuth": {} }
     *     },
     * )
     */
    public function index()
    {
        try {
            $users = User::where('profile_type', 'App\Models\Aluno')->paginate();
            Log::info('Alunos listados: ' . json_encode($users));
        } catch (ModelNotFoundException $e) {
            Log::error(self::NOT_FOUND_MSG);
            return $this->response(self::NOT_FOUND_MSG, Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            Log::error(self::INTERNAL_SERVER_ERROR);
            return $this->response(self::INTERNAL_SERVER_ERROR, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return AlunosResource::collection($users);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/alunos/{id}",
     *     summary="Mostrar um aluno especifico por id",
     *     tags={"Alunos"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(response="200", description="Retorna o aluno especifico por id"),
     *     security={
     *          { "apiAuth": {} }
     *     },
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Buscar por id",
     *          required=true,
     *         ) 
     * )
     */
    public function show(string $id)
    {
        try {
            $aluno = $this->repository->findOrFail($id);
            Log::info('Aluno mostrado: ' . json_encode($aluno));
        } catch (ModelNotFoundException $e) {
            Log::error(self::NOT_FOUND_MSG);
            return $this->response(self::NOT_FOUND_MSG, Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            Log::error(self::INTERNAL_SERVER_ERROR);
            return $this->response(self::INTERNAL_SERVER_ERROR, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new AlunoResource($aluno);
    }

    /**
     * @OA\Patch(
     *     path="/api/v1/alunos/{id}",
     *     summary="Atualizar um aluno especifico por id",
     *     tags={"Alunos"},
     *     description="Atualiza os dados de um aluno especifico",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(response="200", description="Retorna o aluno atualizado"),
     *     security={
     *          { "apiAuth": {} }
     *     },
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="name", type="string", example="Joao"),
     *              @OA\Property(property="birth_date", type="string", example="01-01-2003"),
     *              @OA\Property(property="cpf", type="string", example="111.111.111-13"),
     *              @OA\Property(property="email", type="string", example="joao@email.com"),
     *              @OA\Property(property="address", type="string", example="Rua teste"),
     *              @OA\Property(property="plano", type="string", example="mensal"),
     *              @OA\Property(property="vencimento", type="date", example="01-01-2026"),
     *              @OA\Property(property="status", type="boolean", example="true"),
     *              @OA\Property(property="data_feedback_inicio", type="date", example="01-01-2024"),
     *              @OA\Property(property="data_feedback_fim", type="date", example="01-01-2025"),
     * 
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Buscar por id",
     *          required=true,
     *         )
     )
     * )
     */
    public function update(UpdateAlunoRequest $request, string $id)
    {
        try {
            $aluno = $this->repository->findOrFail($id);
            $user = User::where('profile_type', 'App\Models\Aluno')->where('profile_id', $aluno->id)->first();
            $data = $request->validated();
            $aluno->update($data);
            $user->update($data);
            Log::info('Aluno atualizado: ' . json_encode($aluno));
        } catch (ModelNotFoundException $e) {
            Log::error(self::NOT_FOUND_MSG);
            return $this->response(self::NOT_FOUND_MSG, Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return $this->response($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return new AlunoResource($aluno);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/alunos/{id}",
     *     summary="Deletar um aluno especifico por id",
     *     tags={"Alunos"},
     *     description="Deleta um aluno especifico por id",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(response="200", description="Retorna o aluno deletado"),
     *     security={
     *          { "apiAuth": {} }
     *     },
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Buscar por id",
     *          required=true,
     *         )
     * )
     */
    public function destroy(string $id)
    {
        try {
            $aluno = $this->repository->findOrFail($id);
            $user = User::where('profile_type', 'App\Models\Aluno')->where('profile_id', $aluno->id)->first();
            $aluno->delete();
            $user->delete();
            Log::info('Aluno deletado: ' . $id);
        } catch (ModelNotFoundException $e) {
            Log::error(self::NOT_FOUND_MSG);
            return $this->response(self::NOT_FOUND_MSG, Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            Log::error(self::INTERNAL_SERVER_ERROR);
            return $this->response(self::INTERNAL_SERVER_ERROR, Response::HTTP_INTERNAL_SERVER_ERROR, $e->getTrace());
        }
        return $this->response('Aluno deletado com sucesso!', Response::HTTP_NO_CONTENT);
    }
}
