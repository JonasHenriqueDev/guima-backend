<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if ($this->profile_type === 'App\Models\Aluno') {
            return [
                'id' => $this->id,
                'name' => $this->name,
                'birth_date' => Carbon::parse($this->birth_date)->format('d/m/Y'),
                'cpf' => $this->cpf,
                'address' => $this->address,
                'email' => $this->email,
                'email_verified_at' => Carbon::parse($this->email_verified_at)->format('d/m/Y H:i:s'),
                'plano' => $this->plano,
                'vencimento' => Carbon::parse($this->vencimento)->format('d/m/Y'),
                'status' => $this->status,
                'profile_type' => class_basename($this->profile_type),
                'data_feedback_inicio' => $this->data_feedback_inicio,
                'data_feedback_fim' => $this->data_feedback_fim,
                'created_at' => Carbon::parse($this->created_at)->format('d/m/Y H:i:s'),
                'updated_at' => Carbon::parse($this->updated_at)->format('d/m/Y H:i:s'),
            ];
        } else {
            return [
                'id' => $this->id,
                'name' => $this->name,
                'birth_date' => Carbon::parse($this->birth_date)->format('d/m/Y'),
                'cpf' => $this->cpf,
                'address' => $this->address,
                'email' => $this->email,
                'email_verified_at' => $this->email_verified_at,
                'profile_type' => class_basename($this->profile_type),
                'created_at' => Carbon::parse($this->created_at)->format('d/m/Y H:i:s'),
                'updated_at' => Carbon::parse($this->updated_at)->format('d/m/Y H:i:s'),
            ];
        }
    }
}
