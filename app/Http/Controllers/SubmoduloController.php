<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubmoduloRequest;
use App\Http\Requests\UpdateSubmoduloRequest;
use App\Http\Resources\SubmoduloResource;
use App\Models\Submodulo;
use App\Models\Aula;
use App\Services\ImageService;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SubmoduloController extends Controller
{
    const NOT_FOUND_MSG = 'Submodulo não encontrado!';
    const INTERNAL_SERVER_ERROR = 'Erro interno do servidor!';
    /**
     * @OA\Get(
     *     path="/api/v1/modulos/{modulo_id}/submodulos",
     *     summary="Listar todos os submodulos de um modulo específico",
     *     tags={"Submodulos"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(response="200", description="Retorna a lista de submodulos"),
     *     security={
     *          { "apiAuth": {} }
     *     },
     *     @OA\Parameter(
     *          name="modulo_id",
     *          in="path",
     *          description="Id do modulo",
     *          required=true,
     *         )
     * )
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
     * @OA\Post(
     *     path="/api/v1/modulos/{modulo_id}/submodulos",
     *     summary="Criar um novo submodulo",
     *     tags={"Submodulos"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(response="200", description="Retorna o submodulo criado"),
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
     *              @OA\Property(property="modulo_id", type="integer", example="1"),
     *          )
     *      ),
     * )
     */
    public function store(StoreSubmoduloRequest $request, string $modulo_id)
    {
        $data = $request->validated();
        $data['modulo_id'] = $modulo_id;

        if (isset($data['img_reference'])) {
            $img = $data['img_reference'];

            $path = ImageService::save($img);

            $data['img_reference'] = $path;
        }

        $submodulo = Submodulo::create($data);

        return SubmoduloResource::make($submodulo);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/modulos/{modulo_id}/submodulos/{submodulo_id}",
     *     summary="Mostrar um submodulo específico por id",
     *     tags={"Submodulos"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(response="200", description="Retorna o submodulo específico por id"),
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
    public function show(string $modulo_id, string $submodulo_id)
    {
        try {
            $submodulo = Submodulo::where('modulo_id', $modulo_id)
                ->where('id', $submodulo_id)
                ->with('aulas')
                ->firstOrFail();
        } catch (NotFoundHttpException $e) {
            Log::error(self::NOT_FOUND_MSG);
            return $this->response(self::NOT_FOUND_MSG, Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            Log::error(self::INTERNAL_SERVER_ERROR);
            return $this->error(self::INTERNAL_SERVER_ERROR, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return SubmoduloResource::make($submodulo);
    }

    /**
     * @OA\Patch(
     *     path="/api/v1/modulos/{modulo_id}/submodulos/{submodulo_id}",
     *     summary="Criar um novo submodulo",
     *     tags={"Submodulos"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(response="200", description="Retorna o submodulo criado"),
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
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="titulo", type="string", example="Treinamento"),
     *              @OA\Property(property="descricao", type="string", example="Modulo de treinamento"),
     *              @OA\Property(property="ordem", type="integer", example="3"),
     *              @OA\Property(property="img_reference", type="string", example="null"),
     *              @OA\Property(property="modulo_id", type="integer", example="1"),
     *          )
     *      ),
     * )
     */
    public function update(UpdateSubmoduloRequest $request, string $modulo_id, string $submodulo_id)
    {
        try {
            $submodulo = SubModulo::findOrFail($submodulo_id);

            $request = $request->validated();

            if (isset($request['img_reference'])) {
                $img = $request['img_reference'];

            $path = ImageService::save($img);

                $request['img_reference'] = $path;
            }

            $submodulo->update($request);
        } catch (NotFoundHttpException $e) {
            Log::error(self::NOT_FOUND_MSG);
            return $this->response(self::NOT_FOUND_MSG, Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return $this->error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return SubmoduloResource::make($submodulo);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/modulos/{modulo_id}/submodulos/{submodulo_id}",
     *     summary="Deletar um submodulo específico por id",
     *     tags={"Submodulos"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(response="204", description="Submodulo deletado com sucesso"),
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
    public function destroy(string $modulo_id, string $submodulo_id)
    {
        $submodulo = SubModulo::findOrFail($submodulo_id);

        $imgReference = $submodulo->img_reference;
        $submodulo->delete();
        ImageService::delete($imgReference);

        return $this->response('Submodulo deletado com sucesso.', Response::HTTP_NO_CONTENT);
    }
}
