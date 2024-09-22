<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAlunoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'name' => ['string', 'max:255'],
            'birth_date' => ['date'],
            'cpf' => ['string', 'max:14'],
            'address' => ['string', 'max:255'],
            'email' => ['string', 'email', 'max:255'],
            // 'password' => ['required', 'string', 'min:8', 'confirmed'],
            'plano' => 'string',
            'vencimento' => 'date',
            'status' => 'boolean',
            'data_feedback_inicio' => 'date',
            'data_feedback_fim' => 'date',
        ];
        return $rules;
    }
}
