<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAulaRequest;
use App\Http\Requests\UpdateAulaRequest;
use App\Http\Resources\AulaResource;
use App\Models\Aula;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AulaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(string $modulo_id, string $submodulo_id)
    {
        $aulas = Aula::where('submodulo_id', $submodulo_id)->paginate();
        return AulaResource::collection($aulas);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAulaRequest $request, string $modulo_id)
    {
        $data = $request->validated();

        $data['modulo_id'] = $modulo_id;

        $aula = Aula::create($data);

        return AulaResource::make($aula);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $aula = Aula::findOrFail($id);

        return AulaResource::make($aula);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAulaRequest $request, string $id)
    {
        $request = $request->validated();

        $aula = Aula::findOrFail($id);

        $aula->update($request);

        return AulaResource::make($aula);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $aula = Aula::findOrFail($id);

        $aula->delete();

        return $this->response('Aula deletada com sucesso.', Response::HTTP_NO_CONTENT);
    }
}
