<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProfessorRequest;
use App\Http\Requests\UpdateProfessorRequest;
use App\Http\Resources\ProfessorResource;
use App\Models\Professor;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProfessorController extends Controller
{
    const NOT_FOUND_MSG = 'Professor não encontrado!';
    const INTERNAL_SERVER_ERROR = 'Erro interno do servidor!';

    public function __construct(
        protected Professor $repository,
    ) {
        $this->middleware('auth:sanctum')->except(['store']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $professores = $this->repository->paginate();
        } catch (Exception $e) {
            Log::error(self::INTERNAL_SERVER_ERROR);
        }

        return ProfessorResource::collection($professores);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProfessorRequest $request)
    {
        $data = $request->validated();
        $professor = $this->repository->create($data);
        $professor->user()->create($data);

        return new ProfessorResource($professor);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $professor = $this->repository->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error(self::NOT_FOUND_MSG);
            return $this->response(self::NOT_FOUND_MSG, Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            Log::error(self::INTERNAL_SERVER_ERROR);
            return $this->response(self::INTERNAL_SERVER_ERROR, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return new ProfessorResource($professor);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProfessorRequest $request, string $id)
    {
        try {
            $professor = $this->repository->findOrFail($id);
            $data = $request->validated();
            $professor->update($data);
        } catch (ModelNotFoundException $e) {
            Log::error(self::NOT_FOUND_MSG);
            return $this->response(self::NOT_FOUND_MSG, Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return $this->response($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new ProfessorResource($professor);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
        $professor = $this->repository->findOrFail($id);
        $professor->delete();
        } catch (ModelNotFoundException $e) {
            Log::error(self::NOT_FOUND_MSG);
            return $this->response(self::NOT_FOUND_MSG, Response::HTTP_NOT_FOUND);
        }
        return $this->response('Professor deleted', Response::HTTP_NO_CONTENT);
    }
}
