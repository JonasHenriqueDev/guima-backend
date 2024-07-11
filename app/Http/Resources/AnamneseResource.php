<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AnamneseResource extends JsonResource
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
            'name' => $this->name,
            'cpf' => $this->cpf,
            'email' => $this->email,
            'address' => $this->address,
            'telefone' => $this->telefone,
            'plano' => $this->plano,
            'vencimento' => $this->vencimento,
            'birth_date' => $this->birth_date,
            'altura_peso' => $this->altura_peso,
            'rotina' => $this->rotina,
            'indicacao' => $this->indicacao,
            'profissao' => $this->profissao,
            'objetivo' => $this->objetivo,
            'acompanhamento_anterior' => $this->acompanhamento_anterior,
            'refeicoes_por_dia' => $this->refeicoes_por_dia,
            'quantas_vezes_pode_comer' => $this->quantas_vezes_pode_comer,
            'agua_por_dia' => $this->agua_por_dia,
            'horario_fome' => $this->horario_fome,
            'cafe_manha' => $this->cafe_manha,
            'almoco' => $this->almoco,
            'entre_almoco_jantar' => $this->entre_almoco_jantar,
            'jantar' => $this->jantar,
            'beliscar' => $this->beliscar,
            'mais_alguma_ref' => $this->mais_alguma_ref,
            'alimento_beliscar' => $this->alimento_beliscar,
            'alimentos_dia_dia' => $this->alimentos_dia_dia,
            'nao_alimentos_dia_dia' => $this->nao_alimentos_dia_dia,
            'refeicao_pratica' => $this->refeicao_pratica,
            'balanca' => $this->balanca,
            'airfryer_bolsa' => $this->airfryer_bolsa,
            'alergia' => $this->alergia,
            'bebida_alcoolica' => $this->bebida_alcoolica,
            'peso_1ano_3anos' => $this->peso_1ano_3anos,
            'doce_salgados' => $this->doce_salgados,
            'relacao_comida' => $this->relacao_comida,
            'fazcoco' => $this->fazcoco,
            'horas_sono' => $this->horas_sono,
            'nivel_treino' => $this->nivel_treino,
            'dias_treino' => $this->dias_treino,
            'divisao_treino' => $this->divisao_treino,
            'aparelhos_academia' => $this->aparelhos_academia,
            'plano_saude' => $this->plano_saude,
            'nivel_saude' => $this->nivel_saude,
            'tem_medico' => $this->tem_medico,
            'fumante' => $this->fumante,
            'medicacoes' => $this->medicacoes,
            'doencas_gastrointestinal' => $this->doencas_gastrointestinal,
            'doencas_cardiovascular' => $this->doencas_cardiovascular,
            'doencas_osseas' => $this->doencas_osseas,
            'doenca_autoimune' => $this->doenca_autoimune,
            'doenca_respiratoria' => $this->doenca_respiratoria,
            'doenca_neurologico' => $this->doenca_neurologico,
            'is_aprovada' => $this->is_aprovada,
        ];
    }
}
