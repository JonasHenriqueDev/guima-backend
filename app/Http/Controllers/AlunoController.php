<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAlunoRequest;
use App\Http\Requests\UpdateAlunoRequest;
use App\Http\Resources\AlunoResource;
use App\Http\Resources\AlunosResource;
use App\Models\Aluno;
use App\Models\User;
use Exception;
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

    public function index()
    {
        try {
            $users = User::where('profile_type', 'App\Models\Aluno')->paginate();
            Log::info('Alunos listados: ' . json_encode($users));
        } catch (NotFoundHttpException $e) {
            Log::error(self::NOT_FOUND_MSG);
            return $this->response(self::NOT_FOUND_MSG, Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            Log::error(self::INTERNAL_SERVER_ERROR);
            return $this->error(self::INTERNAL_SERVER_ERROR, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return AlunosResource::collection($users);
    }

    public function show(string $id)
    {
        try {
            $aluno = $this->repository->findOrFail($id);
            Log::info('Aluno mostrado: ' . json_encode($aluno));
        } catch (NotFoundHttpException $e) {
            Log::error(self::NOT_FOUND_MSG);
            return $this->response(self::NOT_FOUND_MSG, Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            Log::error(self::INTERNAL_SERVER_ERROR);
            return $this->response(self::INTERNAL_SERVER_ERROR, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new AlunoResource($aluno);
    }

    public function update(UpdateAlunoRequest $request, string $id)
    {
        try {
            $aluno = $this->repository->findOrFail($id);
            $user = User::where('profile_type', 'App\Models\Aluno')->where('profile_id', $aluno->id)->first();
            $data = $request->validated();
            $aluno->update($data);
            $user->update($data);
            Log::info('Aluno atualizado: ' . json_encode($aluno));
        } catch (NotFoundHttpException $e) {
            Log::error(self::NOT_FOUND_MSG);
            return $this->response(self::NOT_FOUND_MSG, Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return $this->response($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return new AlunoResource($aluno);
    }

    public function destroy(string $id)
    {
        try {
            $aluno = $this->repository->findOrFail($id);
            $user = User::where('profile_type', 'App\Models\Aluno')->where('profile_id', $aluno->id)->first();
            $aluno->delete();
            $user->delete();
            Log::info('Aluno deletado: ' . $id);
        } catch (NotFoundHttpException $e) {
            Log::error(self::NOT_FOUND_MSG);
            return $this->response(self::NOT_FOUND_MSG, Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            Log::error(self::INTERNAL_SERVER_ERROR);
            return $this->response(self::INTERNAL_SERVER_ERROR, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return $this->response('Aluno deletado com sucesso!', Response::HTTP_NO_CONTENT);
    }
}
