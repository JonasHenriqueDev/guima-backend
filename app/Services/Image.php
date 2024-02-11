<?php

namespace App\Services;

use Illuminate\Support\Facades\Response;

class Image
{
    public static function get(string $path)
    {
        try {
            $path = storage_path("app/public/{$path}");
            return Response::download($path);
        } catch (\Exception $e) {
            return null;
        }
    }
}
