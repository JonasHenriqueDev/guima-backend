<?php

namespace App\Http\Resources;

use App\Models\Modulo;
use App\Models\Submodulo;
use App\Services\Image;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class AulaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'titulo' => $this->titulo,
            'descricao' => $this->descricao,
            'img_reference' => $this->img_reference,
            'url_id' => $this->url_id,
            'ordem' => $this->ordem,
            'submodulo_id' => $this->submodulo_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
