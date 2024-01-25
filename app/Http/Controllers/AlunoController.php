<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAlunoRequest;
use App\Http\Requests\UpdateAlunoRequest;
use App\Http\Resources\AlunoResource;
use App\Models\Aluno;
use App\Models\User;
use Illuminate\Http\Response;

class AlunoController extends Controller
{
    public function __construct(
        protected Aluno $repository,
    ) {
        $this->middleware('auth:sanctum')->except(['store']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $alunos = User::where('profile_type', 'aluno')->paginate();

        return AlunoResource::collection($alunos);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAlunoRequest $request)
    {
        $data = $request->validated();
        $aluno = $this->repository->create();
        $aluno->user()->create($data);

        return new AlunoResource($aluno);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $aluno = $this->repository->findOrFail($id);

        return new AlunoResource($aluno);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAlunoRequest $request, string $id)
    {
        $aluno = $this->repository->findOrFail($id);
        $data = $request->validated();
        $aluno->update($data);

        return new AlunoResource($aluno);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $aluno = $this->repository->findOrFail($id);
        $aluno->delete();

        return $this->response('Aluno deleted', Response::HTTP_NO_CONTENT);
    }
}
