<?php

namespace App\Http\Controllers;

use App\Services\Image;
use App\Services\ImageService;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    }

    /**
     * @OA\Get(
     *     path="/api/v1/image",
     *     summary="Mostrar uma imagem específica pela sua referência",
     *     tags={"Imagens"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(response="200", description="Retorna a imagem específica pela sua referência"),
     *     security={
     *          { "apiAuth": {} }
     *     },
     *      @OA\Parameter(
     *          name="reference",
     *          in="query",
     *          description="Buscar por referência da imagem    ",
     *          required=true,
     *          example="images/xTTltUkTRHNWHxs4jnWxAR1YpN56ujWf9fFpl4VP.jpg"
     *         ) 
     * )
     */
    public function show()
    {
        $reference = request()->query('reference');
        return ImageService::get($reference);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $path)
    {
        return ImageService::delete($path);
    }
}
