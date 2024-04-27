<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAulaRequest;
use App\Http\Requests\UpdateAulaRequest;
use App\Http\Resources\AulaResource;
use App\Models\Aula;
use Illuminate\Http\Response;

class AulaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * @OA\Get(
     *     path="/api/v1/modulos/{modulo_id}/submodulos/{submodulo_id}/aulas",
     *     summary="Listar todos as aulas do submodulo",
     *     tags={"Aulas"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(response="200", description="Retorna a lista de alunos"),
     *     security={
     *          { "apiAuth": {} }
     *     },
     *      @OA\Parameter(
     *          name="modulo_id",
     *          in="path",
     *          description="Id do modulo",
     *          required=true,
     *         ),
     *      @OA\Parameter(
     *          name="submodulo_id",
     *          in="path",
     *          description="Id do submodulo",
     *          required=true,
     *         ) 
     * )
     */
    public function index(string $modulo_id, string $submodulo_id)
    {
        $aulas = Aula::where('submodulo_id', $submodulo_id)->paginate();
        return AulaResource::collection($aulas);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/modulos/{modulo_id}/submodulos/{submodulo_id}/aulas",
     *     summary="Criar uma nova aula",
     *     tags={"Aulas"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(response="200", description="Retorna a aula criada"),
     *     security={
     *          { "apiAuth": {} }
     *     },
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="titulo", type="string", example="Supino Reto"),
     *              @OA\Property(property="descricao", type="string", example="Supino Reto"),
     *              @OA\Property(property="img_reference", type="string", example="null"),
     *              @OA\Property(property="url_id", type="string", example="dQw4w9WgXcQ"),
     *              @OA\Property(property="ordem", type="integer", example="6"),
     *              @OA\Property(property="submodulo_id", type="integer", example="2"),
     *              @OA\Property(property="modulo_id", type="integer", example="1"),
     *          )
     *      ),
     * )
     */
    public function store(StoreAulaRequest $request, string $modulo_id, string $submodulo_id)
    {
        $data = $request->validated();

        $data['modulo_id'] = $modulo_id;
        $data['submodulo_id'] = $submodulo_id;


        if (isset($request['img_reference'])) {
            $img = $request['img_reference'];

            $path = $img->store('images', 'public');

            $data['img_reference'] = $path;
        }

        $aula = Aula::create($data);

        return AulaResource::make($aula);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/modulos/{modulo_id}/submodulos/{submodulo_id}/aulas/{id}",
     *     summary="Mostrar uma aula específica por id",
     *     tags={"Aulas"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(response="200", description="Retorna a aula específica por id"),
     *     security={
     *          { "apiAuth": {} }
     *     },
     *      @OA\Parameter(
     *          name="modulo_id",
     *          in="path",
     *          description="Id do modulo",
     *          required=true,
     *         ),
     *      @OA\Parameter(
     *          name="submodulo_id",
     *          in="path",
     *          description="Id do submodulo",
     *          required=true,
     *         ),
     *      @OA\Parameter(
     *          name="aula_id",
     *          in="path",
     *          description="Id da aula",
     *          required=true,
     *        )
     * )
     */
    public function show(string $modulo_id, string $submodulo_id, string $aula_id)
    {
        $aula = Aula::findOrFail($aula_id);

        return AulaResource::make($aula);
    }

    /**
     * @OA\Patch(
     *     path="/api/v1/modulos/{modulo_id}/submodulos/{submodulo_id}/aulas/{aula_id}",
     *     summary="Atualizar uma aula específica por id",
     *     tags={"Aulas"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(response="200", description="Retorna a aula atualizada por id"),
     *     security={
     *          { "apiAuth": {} }
     *     },
     *      @OA\Parameter(
     *          name="modulo_id",
     *          in="path",
     *          description="Id do modulo",
     *          required=true,
     *         ),
     *      @OA\Parameter(
     *          name="submodulo_id",
     *          in="path",
     *          description="Id do submodulo",
     *          required=true,
     *         ),
     *      @OA\Parameter(
     *          name="aula_id",
     *          in="path",
     *          description="Id da aula",
     *          required=true,
     *        ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="titulo", type="string", example="Supino Reto"),
     *              @OA\Property(property="descricao", type="string", example="Supino Reto"),
     *              @OA\Property(property="img_reference", type="string", example="null"),
     *              @OA\Property(property="url_id", type="string", example="dQw4w9WgXcQ"),
     *              @OA\Property(property="ordem", type="integer", example="6"),
     *              @OA\Property(property="submodulo_id", type="integer", example="2"),
     *              @OA\Property(property="modulo_id", type="integer", example="1"),
     *          )
     *      ),
     * )
     */
    public function update(UpdateAulaRequest $request, string $modulo_id, string $submodulo_id, string $aula_id)
    {
        $request = $request->validated();

        $aula = Aula::findOrFail($aula_id);

        if (isset($request['img_reference'])) {
            $img = $request['img_reference'];

            $path = $img->store('images', 'public');

            $request['img_reference'] = $path;
        }

        $aula->update($request);

        return AulaResource::make($aula);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/modulos/{modulo_id}/submodulos/{submodulo_id}/aulas/{aula_id}",
     *     summary="Deletar uma aula específica por id",
     *     description="Deletar uma aula específica por id",
     *     tags={"Aulas"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(response="204", description="Aula deletado com sucesso"),
     *     security={
     *          { "apiAuth": {} }
     *     },
     *      @OA\Parameter(
     *          name="modulo_id",
     *          in="path",
     *          description="Id do modulo",
     *          required=true,
     *         ),
     *      @OA\Parameter(
     *          name="submodulo_id",
     *          in="path",
     *          description="Id do submodulo",
     *          required=true,
     *         ),
     *      @OA\Parameter(
     *          name="aula_id",
     *          in="path",
     *          description="Id da aula",
     *          required=true,
     *        )
     * )
     */
    public function destroy(string $modulo_id, string $submodulo_id, string $aula_id)
    {
        $aula = Aula::findOrFail($aula_id);

        $aula->delete();

        return $this->response('Aula deletada com sucesso.', Response::HTTP_NO_CONTENT);
    }
}
