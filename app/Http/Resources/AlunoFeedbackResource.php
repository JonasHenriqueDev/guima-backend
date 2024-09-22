<?php

namespace App\Http\Resources;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AlunoFeedbackResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = User::where('profile_type', 'App\Models\Aluno')->where('profile_id', $this->id)->first();

        $arr = [
            'aluno_id' => $this->id,
            'name' => $user->name,
            'cpf' => $user->cpf,
            'email' => $user->email,
            'plano' => $this->plano,
            'data_feedback_inicio' => $this->data_feedback_inicio,
            'data_feedback_fim' => $this->data_feedback_fim
        ];

        //dd($arr);

        return $arr;
    }
}
