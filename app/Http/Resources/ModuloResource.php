<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ModuloResource extends JsonResource
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
            'ordem' => $this->ordem,
            'submodulos' => SubmoduloResource::collection($this->whenLoaded('submodulos')),
        ];
    }
}
