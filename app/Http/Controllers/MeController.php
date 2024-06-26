<?php

namespace App\Http\Controllers;

use App\Http\Resources\MeResource;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
        try {
            $user = Auth::user();
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return $this->error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new MeResource($user);
    }
}
