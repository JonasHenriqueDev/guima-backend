<?php

namespace App\Http\Controllers;

use App\Http\Resources\MeResource;
use Illuminate\Support\Facades\Auth;

class MeController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/me",
     *     summary="Mostrar o usuário autenticado",
     *     tags={"Me"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(response="200", description="Retorna o usuário autenticado"),
     *     security={
     *          { "apiAuth": {} }
     *     },
     * )
     */
    public function me()
    {
        $user = Auth::user();

        return new MeResource($user);
    }
}
