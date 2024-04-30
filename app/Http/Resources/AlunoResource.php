<?php

namespace App\Http\Resources;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AlunoResource extends UserResource
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
            'id' => $this->id,
            'name' => $user->name,
            'birth_date' => Carbon::parse($user->birth_date)->format('d/m/Y'),
            'cpf' => $user->cpf,
            'address' => $user->address,
            'email' => $user->email,
            'email_verified_at' => Carbon::parse($user->email_verified_at)->format('d/m/Y H:i:s'),
            'created_at' => Carbon::parse($user->created_at)->format('d/m/Y H:i:s'),
            'updated_at' => Carbon::parse($user->updated_at)->format('d/m/Y H:i:s'),
            'plano' => $this->plano,
            'vencimento' => Carbon::parse($this->vencimento)->format('d/m/Y'),
            'status' => $this->status
        ];

        //dd($arr);

        return $arr;
    }
}
