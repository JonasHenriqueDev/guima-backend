<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAnamneseRequest extends FormRequest
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
        return [
            //dados pessoais
            'name' => ['required', 'string', 'max:255'],
            'cpf' => ['required', 'string', 'max:14'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'address' => ['required', 'string', 'max:255'],
            'telefone' => ['required', 'string', 'max:15'],
            'plano' => 'string',
            'vencimento' => 'string',
            'birth_date' => ['required', 'date'],
            'altura_peso' => ['required', 'string', 'max:255'],
            'rotina' => ['required', 'string'],
            'indicacao' => ['required', 'string', 'max:255'],
            'profissao' => ['required', 'string', 'max:255'],
            'objetivo' => ['string', 'max:255'],
            'acompanhamento_anterior' => ['string', 'max:255'],

            //alimentacao/nutricao
            'refeicoes_por_dia' => ['required', 'string', 'max:255'],
            'quantas_vezes_pode_comer' => ['required', 'string', 'max:255'],
            'agua_por_dia' => ['required', 'string', 'max:255'],
            'horario_fome' => ['required', 'string', 'max:255'],
            'cafe_manha' => ['required', 'string', 'max:255'],
            'almoco' => ['required', 'string', 'max:255'],
            'entre_almoco_jantar' => ['required', 'string', 'max:255'],
            'jantar' => ['required', 'string', 'max:255'],
            'beliscar' => ['required', 'string', 'max:255'],
            'mais_alguma_ref' => ['string', 'max:255'],
            'alimento_beliscar' => ['required', 'string', 'max:255'],
            'alimentos_dia_dia' => ['required', 'string', 'max:255'],
            'nao_alimentos_dia_dia' => ['required', 'string', 'max:255'],
            'refeicao_pratica' => ['required', 'string', 'max:255'],
            'balanca' => ['required', 'string', 'max:255'],
            'airfryer_bolsa' => ['required', 'string', 'max:255'],
            'alergia' => ['required', 'string', 'max:255'],
            'bebida_alcoolica' => ['required', 'string', 'max:255'],

            //comportamento e peso
            'peso_1ano_3anos' => ['required', 'string', 'max:255'],
            'doce_salgados' => ['required', 'string', 'max:255'],
            'relacao_comida' => ['required', 'string', 'max:255'],
            'fazcoco' => ['required', 'string', 'max:255'],
            'horas_sono' => ['required', 'string', 'max:255'],

            //exercicios
            'nivel_treino' => ['required', 'string', 'max:255'],
            'dias_treino' => ['required', 'string', 'max:255'],
            'divisao_treino' => ['required', 'string', 'max:255'],

            //academia 
            'aparelhos_academia' => ['required', 'string', 'max:255'],

            //saude
            'plano_saude' => ['required', 'string', 'max:255'],
            'nivel_saude' => ['required', 'string', 'max:255'],
            'tem_medico' => ['required', 'string', 'max:255'],
            'fumante' => ['required', 'string', 'max:255'],
            'medicacoes' => ['required', 'string', 'max:255'],
            'doencas_gastrointestinal' => ['required', 'string', 'max:255'],
            'doencas_cardiovascular' => ['required', 'string', 'max:255'],
            'doencas_osseas' => ['required', 'string', 'max:255'],
            'doenca_autoimune' => ['required', 'string', 'max:255'],
            'doenca_respiratoria' => ['required', 'string', 'max:255'],
            'doenca_neurologico' => ['required', 'string', 'max:255'],
        ];
    }
}
