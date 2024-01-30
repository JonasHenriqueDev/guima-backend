<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVideoAulaRequest;
use App\Http\Requests\UpdateVideoAulaRequest;
use App\Http\Resources\VideoAulaResource;
use App\Models\VideoAula;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class VideoAulaController extends Controller
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
        $aulas = VideoAula::where('submodulo_id', $submodulo_id)->paginate();
        return VideoAulaResource::collection($aulas);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVideoAulaRequest $request, string $modulo_id)
    {
        $data = $request->validated();

        $data['modulo_id'] = $modulo_id;

        $aula = VideoAula::create($data);

        return VideoAulaResource::make($aula);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $aula = VideoAula::findOrFail($id);

        return VideoAulaResource::make($aula);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVideoAulaRequest $request, string $id)
    {
        $request = $request->validated();

        $aula = VideoAula::findOrFail($id);

        $aula->update($request);

        return VideoAulaResource::make($aula);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $aula = VideoAula::findOrFail($id);

        $aula->delete();

        return $this->response('Aula deletada com sucesso.', Response::HTTP_NO_CONTENT);
    }
}
