<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreUpdateFormRequest extends FormRequest
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
        if ($this->method() === 'PATCH') {
            // $rules['password'] = ['nullable', 'string', 'min:8', 'confirmed'];

            $rules['email'] = ['string', 'email', 'max:255', 'unique:users,email,' . $this->id];
        } else {
            $rules = [
                'name' => ['required', 'string', 'max:255'],
                'birth_date' => ['required', 'date'],
                'cpf' => ['required', 'string', 'max:14'],
                'address' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                // 'password' => ['required', 'string', 'min:8', 'confirmed'],
                'profile_type' => ['required', 'string', 'in:professor,aluno'],
                'plano' => 'string',
                'vencimento' => 'date',
                'status' => 'boolean',
            ];
        }

        return $rules;
    }
}
