<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use Nette\Utils\Random;

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

    public static function save($img)
    {

        $image = Image::read($img);

        $image->scale(height: 500);

        $imageData = $image->encode();

        $path = str_replace(' ', '', 'images/' . Carbon::now() . Random::generate() . '.' . $img->getClientOriginalExtension());

        Storage::put($path, $imageData);

        return $path;
    }

    public static function delete(string $path)
    {
        try {
            Storage::delete($path);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Imagem não encontrada',
                'status' => 404
            ], HttpResponse::HTTP_NOT_FOUND);
        }
    }
}
