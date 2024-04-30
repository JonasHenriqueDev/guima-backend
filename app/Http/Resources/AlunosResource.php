<?php

namespace App\Http\Resources;

use App\Models\Aluno;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AlunosResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */


    public function toArray(Request $request): array
    {
        $alunos = Aluno::where('id', $this->profile_id)->first();
        return [
            'id' => $alunos->id,
            'name' => $this->name,
            'birth_date' => Carbon::parse($this->birth_date)->format('d/m/Y'),
            'cpf' => $this->cpf,
            'address' => $this->address,
            'email' => $this->email,
            'email_verified_at' => Carbon::parse($this->email_verified_at)->format('d/m/Y H:i:s'),
            'created_at' => Carbon::parse($this->created_at)->format('d/m/Y H:i:s'),
            'updated_at' => Carbon::parse($this->updated_at)->format('d/m/Y H:i:s'),
            'plano' => $alunos->plano,
            'vencimento' => Carbon::parse($this->vencimento)->format('d/m/Y'),
            'status' => $alunos->status
        ];
    }
}
