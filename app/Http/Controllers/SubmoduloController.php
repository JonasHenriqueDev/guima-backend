<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubmoduloRequest;
use App\Http\Requests\UpdateSubmoduloRequest;
use App\Http\Resources\SubmoduloResource;
use App\Models\Submodulo;
use App\Models\Aula;
use Illuminate\Http\Response;

class SubmoduloController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(string $modulo_id)
    {
        $submodulos = Submodulo::where('modulo_id', $modulo_id)
            ->with('aulas')
            ->orderBy('ordem')
            ->paginate();
        return SubmoduloResource::collection($submodulos);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSubmoduloRequest $request)
    {
        $request = $request->validated();

        $submodulo = Submodulo::create($request);

        return SubmoduloResource::make($submodulo);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $moduloId, string $submoduloId)
    {
        $submodulo = Submodulo::where('modulo_id', $moduloId)
        ->where('id', $submoduloId)
        ->with('aulas')
        ->firstOrFail();

        return SubmoduloResource::make($submodulo);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSubmoduloRequest $request, string $id)
    {
        $submodulo = SubModulo::findOrFail($id);

        $request = $request->validated();

        $submodulo->update($request);

        return SubmoduloResource::make($submodulo);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $submodulo = SubModulo::findOrFail($id);

        $submodulo->delete();

        return $this->response('Submodulo deletado com sucesso.', Response::HTTP_NO_CONTENT);
    }
}
