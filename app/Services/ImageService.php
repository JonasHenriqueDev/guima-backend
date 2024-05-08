<?php

namespace App\Services;

use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;


class ImageService
{
    public static function get(string $path)
    {
        try {
            $path = ("{$path}");
            return Storage::download($path);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Imagem não encontrada',
                'status' => 404
            ], HttpResponse::HTTP_NOT_FOUND);
        }
    }
}
