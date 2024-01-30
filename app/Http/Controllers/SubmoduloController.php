<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubmoduloRequest;
use App\Http\Requests\UpdateSubmoduloRequest;
use App\Http\Resources\SubmoduloResource;
use App\Models\Submodulo;
use App\Models\VideoAula;

class SubmoduloController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(string $modulo_id)
    {
        $submodulos = Submodulo::where('modulo_id', $modulo_id)->with('video_aulas')->paginate();
        return SubmoduloResource::collection($submodulos);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSubmoduloRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $moduloId, string $submoduloId)
    {
        $submodulo = Submodulo::where('modulo_id', $moduloId)->where('id', $submoduloId)->with('video_aulas')->firstOrFail();

        return SubmoduloResource::make($submodulo);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSubmoduloRequest $request, Submodulo $submodulo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Submodulo $submodulo)
    {
        //
    }
}
