<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAnamneseRequest extends FormRequest
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
        $anamneseId = $this->route('anamnese');

        return [
            // Dados pessoais
            'name' => ['sometimes', 'string', 'max:255'],
            'cpf' => ['sometimes', 'string', 'max:14'],
            'email' => [
                'sometimes',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($anamneseId),
            ],
            'address' => ['sometimes', 'string', 'max:255'],
            'telefone' => ['sometimes', 'string', 'max:15'],
            'plano' => 'nullable|string',
            'vencimento' => 'nullable|string',
            'birth_date' => ['sometimes', 'date'],
            'altura_peso' => ['sometimes', 'string', 'max:255'],
            'rotina' => ['sometimes', 'string'],
            'indicacao' => ['sometimes', 'string', 'max:255'],
            'profissao' => ['sometimes', 'string', 'max:255'],
            'objetivo' => ['nullable', 'string', 'max:255'],
            'acompanhamento_anterior' => ['nullable', 'string', 'max:255'],

            // Alimentação/nutrição
            'refeicoes_por_dia' => ['sometimes', 'string', 'max:255'],
            'quantas_vezes_pode_comer' => ['sometimes', 'string', 'max:255'],
            'agua_por_dia' => ['sometimes', 'string', 'max:255'],
            'horario_fome' => ['sometimes', 'string', 'max:255'],
            'cafe_manha' => ['sometimes', 'string', 'max:255'],
            'almoco' => ['sometimes', 'string', 'max:255'],
            'entre_almoco_jantar' => ['sometimes', 'string', 'max:255'],
            'jantar' => ['sometimes', 'string', 'max:255'],
            'beliscar' => ['sometimes', 'string', 'max:255'],
            'mais_alguma_ref' => ['nullable', 'string', 'max:255'],
            'alimento_beliscar' => ['sometimes', 'string', 'max:255'],
            'alimentos_dia_dia' => ['sometimes', 'string', 'max:255'],
            'nao_alimentos_dia_dia' => ['sometimes', 'string', 'max:255'],
            'refeicao_pratica' => ['sometimes', 'string', 'max:255'],
            'balanca' => ['sometimes', 'string', 'max:255'],
            'airfryer_bolsa' => ['sometimes', 'string', 'max:255'],
            'alergia' => ['sometimes', 'string', 'max:255'],
            'bebida_alcoolica' => ['sometimes', 'string', 'max:255'],

            // Comportamento e peso
            'peso_1ano_3anos' => ['sometimes', 'string', 'max:255'],
            'doce_salgados' => ['sometimes', 'string', 'max:255'],
            'relacao_comida' => ['sometimes', 'string', 'max:255'],
            'fazcoco' => ['sometimes', 'string', 'max:255'],
            'horas_sono' => ['sometimes', 'string', 'max:255'],

            // Exercícios
            'nivel_treino' => ['sometimes', 'string', 'max:255'],
            'dias_treino' => ['sometimes', 'string', 'max:255'],
            'divisao_treino' => ['sometimes', 'string', 'max:255'],

            // Academia
            'aparelhos_academia' => ['sometimes', 'string', 'max:255'],

            // Saúde
            'plano_saude' => ['sometimes', 'string', 'max:255'],
            'nivel_saude' => ['sometimes', 'string', 'max:255'],
            'tem_medico' => ['sometimes', 'string', 'max:255'],
            'fumante' => ['sometimes', 'string', 'max:255'],
            'medicacoes' => ['sometimes', 'string', 'max:255'],
            'doencas_gastrointestinal' => ['sometimes', 'string', 'max:255'],
            'doencas_cardiovascular' => ['sometimes', 'string', 'max:255'],
            'doencas_osseas' => ['sometimes', 'string', 'max:255'],
            'doenca_autoimune' => ['sometimes', 'string', 'max:255'],
            'doenca_respiratoria' => ['sometimes', 'string', 'max:255'],
            'doenca_neurologico' => ['sometimes', 'string', 'max:255'],
        ];
    }
}
