<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreModuloRequest;
use App\Http\Requests\UpdateModuloRequest;
use App\Http\Resources\ModuloResource;
use App\Models\Modulo;
use App\Services\ImageService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Mockery\Matcher\Not;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ModuloController extends Controller
{
    const NOT_FOUND_MSG = 'Modulo não encontrado!';
    const INTERNAL_SERVER_ERROR = 'Erro interno do servidor!';

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
        try {
            $modulos = Modulo::paginate();
        } catch (ModelNotFoundException $e) {
            Log::error(self::NOT_FOUND_MSG);
            return $this->response(self::NOT_FOUND_MSG, Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            Log::error(self::INTERNAL_SERVER_ERROR);
            return $this->response(self::INTERNAL_SERVER_ERROR, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
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
        try {
            $modulo = Modulo::where('id', $id)->with('submodulos')->firstOrFail();
        } catch (ModelNotFoundException $e) {
            Log::error(self::NOT_FOUND_MSG);
            return $this->response(self::NOT_FOUND_MSG, Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            Log::error(self::INTERNAL_SERVER_ERROR);
            return $this->response(self::INTERNAL_SERVER_ERROR, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

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
        try {
            $modulo = Modulo::findOrFail($id);

            $request = $request->validated();

            if (isset($request['img_reference'])) {
                $img = $request['img_reference'];

                $path = ImageService::save($img);

                $request['img_reference'] = $path;
            }

            $modulo->update($request);

            return ModuloResource::make($modulo);
        } catch (ModelNotFoundException $e) {
            Log::error(self::NOT_FOUND_MSG);
            return $this->response(self::NOT_FOUND_MSG, Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return $this->response($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
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
        try {
            $modulo = Modulo::findOrFail($id);

            $imgReference = $modulo->img_reference;
            $modulo->delete();
            ImageService::delete($imgReference);
        } catch (ModelNotFoundException $e) {
            Log::error(self::NOT_FOUND_MSG);
            return $this->response(self::NOT_FOUND_MSG, Response::HTTP_NOT_FOUND);
        }
        return $this->response('Modulo deletado com sucesso.', Response::HTTP_NO_CONTENT);
    }
}
