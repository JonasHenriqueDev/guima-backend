<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreModuloRequest;
use App\Http\Requests\UpdateModuloRequest;
use App\Http\Resources\ModuloResource;
use App\Models\Modulo;
use App\Services\ImageService;
use Illuminate\Http\Response;

class ModuloController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * @OA\Get(
     *     path="/api/v1/modulos",
     *     summary="Listar todos os modulos",
     *     tags={"Modulos"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(response="200", description="Retorna a lista de modulos"),
     *     security={
     *          { "apiAuth": {} }
     *     },
     * )
     */
    public function index()
    {
        $modulos = Modulo::with('submodulos')->paginate();
        return ModuloResource::collection($modulos);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/modulos",
     *     summary="Criar um novo modulo",
     *     tags={"Modulos"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(response="200", description="Retorna o modulo criado"),
     *     security={
     *          { "apiAuth": {} }
     *     },
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="titulo", type="string", example="Treinamento"),
     *              @OA\Property(property="descricao", type="string", example="Modulo de treinamento"),
     *              @OA\Property(property="ordem", type="integer", example="3"),
     *              @OA\Property(property="img_reference", type="string", example="null"),
     *          )
     *      ),
     * )
     */
    public function store(StoreModuloRequest $request)
    {
        $request = $request->validated();

        if (isset($request['img_reference'])) {
            $img = $request['img_reference'];
            
            $path = ImageService::save($img);

            $request['img_reference'] = $path;
        }

        $modulo = Modulo::create($request);

        return ModuloResource::make($modulo);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/modulos/{modulo_id}",
     *     summary="Mostrar um modulo específico por id",
     *     tags={"Modulos"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(response="200", description="Retorna o modulo específico por id"),
     *     security={
     *          { "apiAuth": {} }
     *     },
     *      @OA\Parameter(
     *          name="modulo_id",
     *          in="path",
     *          description="Id do modulo",
     *          required=true,
     *         )
     * )
     */
    public function show(string $id)
    {
        $modulo = Modulo::where('id', $id)->with('submodulos')->firstOrFail();

        return ModuloResource::make($modulo);
    }

    /**
     * @OA\Patch(
     *     path="/api/v1/modulos/{modulo_id}",
     *     summary="Atualizar um modulo específico por id",
     *     tags={"Modulos"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(response="200", description="Retorna o modulo atualizado por id"),
     *     security={
     *          { "apiAuth": {} }
     *     },
     *      @OA\Parameter(
     *          name="modulo_id",
     *          in="path",
     *          description="Id do modulo",
     *          required=true,
     *         ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="titulo", type="string", example="Treinamento"),
     *              @OA\Property(property="descricao", type="string", example="Modulo de treinamento"),
     *              @OA\Property(property="ordem", type="integer", example="3"),
     *              @OA\Property(property="img_reference", type="string", example="null"),
     *          )
     *      ),
     * )
     */
    public function update(UpdateModuloRequest $request, string $id)
    {
        $modulo = Modulo::findOrFail($id);

        $request = $request->validated();

        if (isset($request['img_reference'])) {
            $img = $request['img_reference'];

            $path = ImageService::save($img);

            $request['img_reference'] = $path;
        }

        $modulo->update($request);

        return ModuloResource::make($modulo);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/modulos/{modulo_id}",
     *     summary="Deletar um modulo específico por id",
     *     tags={"Modulos"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(response="204", description="Modulo deletado com sucesso"),
     *     security={
     *          { "apiAuth": {} }
     *     },
     *      @OA\Parameter(
     *          name="modulo_id",
     *          in="path",
     *          description="Id do modulo",
     *          required=true,
     *         )
     * )
     */
    public function destroy(string $id)
    {
        $modulo = Modulo::findOrFail($id);
        
        $imgReference = $modulo->img_reference;
        $modulo->delete();
        ImageService::delete($imgReference);

        return $this->response('Modulo deletado com sucesso.', Response::HTTP_NO_CONTENT);
    }
}
