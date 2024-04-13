<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAlunoRequest;
use App\Http\Requests\UpdateAlunoRequest;
use App\Http\Resources\AlunoResource;
use App\Http\Resources\AlunosResource;
use App\Models\Aluno;
use App\Models\User;
use Illuminate\Http\Response;
use OpenApi\Annotations as OA;


class AlunoController extends Controller
{
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


        $users = User::where('profile_type', 'App\Models\Aluno')->paginate();


        return AlunosResource::collection($users);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/alunos/{id}",
     *     summary="Mostrar um aluno específico por id",
     *     tags={"Alunos"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(response="200", description="Retorna o aluno específico por id"),
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
        $aluno = $this->repository->findOrFail($id);

        return new AlunoResource($aluno);
    }

    /**
     * @OA\Patch(
     *     path="/api/v1/alunos/{id}",
     *     summary="Atualizar um aluno específico por id",
     *     tags={"Alunos"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(response="200", description="Retorna o aluno atualizado por id"),
     *     security={
     *          { "apiAuth": {} }
     *     },
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Atualizar por id",
     *          required=true,
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="name", type="string", example="João da Silva"),
     *              @OA\Property(property="birth_date", type="date", example="1990-12-31"),
     *              @OA\Property(property="cpf", type="string", example="123.456.789-00"),
     *              @OA\Property(property="address", type="string", example="Rua das Flores, 123"),
     *              @OA\Property(property="email", type="string", example="joao@email.com"),
     *              @OA\Property(property="password", type="string", example="12345678"),
     *              @OA\Property(property="plano", type="enum", example="mensal"),
     *              @OA\Property(property="vencimento", type="date", example="2024-12-31"),
     *              @OA\Property(property="status", type="boolean", example="true"),
     *          )
     *      ),
     * )
     */
    public function update(UpdateAlunoRequest $request, string $id)
    {
        $aluno = $this->repository->findOrFail($id);
        $user = User::where('profile_type', 'App\Models\Aluno')->where('profile_id', $aluno->id)->first();
        $data = $request->validated();
        $aluno->update($data);
        $user->update($data);

        return new AlunoResource($aluno);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/alunos/{id}",
     *     summary="Deletar um aluno específico por id",
     *     description="Deletar um aluno específico por id",
     *     tags={"Alunos"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(response="204", description="Aluno deletado com sucesso"),
     *     security={
     *          { "apiAuth": {} }
     *     },
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Id do aluno",
     *          required=true,
     *         ) 
     * )
     */
    public function destroy(string $id)
    {
        $aluno = $this->repository->findOrFail($id);
        $aluno->delete();

        return $this->response('Aluno deleted', Response::HTTP_NO_CONTENT);
    }
}
