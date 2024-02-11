<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreModuloRequest;
use App\Http\Requests\UpdateModuloRequest;
use App\Http\Resources\ModuloResource;
use App\Models\Modulo;
use Illuminate\Http\Response;

class ModuloController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $modulos = Modulo::with('submodulos')->paginate();
        return ModuloResource::collection($modulos);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreModuloRequest $request)
    {
        $request = $request->validated();

        if (isset($request['img_reference'])) {
            $img = $request['img_reference'];

            $path = $img->store('images/aulas', 'public');

            $request['img_reference'] = $path;
        }

        $modulo = Modulo::create($request);

        return ModuloResource::make($modulo);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $modulo = Modulo::where('id', $id)->with('submodulos')->firstOrFail();

        return ModuloResource::make($modulo);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateModuloRequest $request, string $id)
    {
        $modulo = Modulo::findOrFail($id);

        $request = $request->validated();

        if (isset($request['img_reference'])) {
            $img = $request['img_reference'];

            $path = $img->store('images', 'public');

            $request['img_reference'] = $path;
        }

        $modulo->update($request);

        return ModuloResource::make($modulo);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $modulo = Modulo::findOrFail($id);

        $modulo->delete();

        return $this->response('Modulo deletado com sucesso.', Response::HTTP_NO_CONTENT);
    }
}
