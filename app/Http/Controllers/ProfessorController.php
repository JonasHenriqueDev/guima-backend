<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProfessorRequest;
use App\Http\Requests\UpdateProfessorRequest;
use App\Http\Resources\ProfessorResource;
use App\Models\Professor;
use App\Models\User;
use Illuminate\Http\Response;

class ProfessorController extends Controller
{
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
        $professores = User::where('profile_type', 'aluno')->paginate();

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
        $professor = $this->repository->findOrFail($id);

        return new ProfessorResource($professor);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProfessorRequest $request, string $id)
    {
        $professor = $this->repository->findOrFail($id);
        $data = $request->validated();
        $professor->update($data);

        return new ProfessorResource($professor);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $professor = $this->repository->findOrFail($id);
        $professor->delete();

        return $this->response('Professor deleted', Response::HTTP_NO_CONTENT);
    }
}
