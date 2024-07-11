<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anamnese extends Model
{
    use HasFactory;

    protected $fillable = [
        // Dados pessoais
        'name',
        'cpf',
        'email',
        'address',
        'telefone',
        'plano',
        'vencimento',
        'birth_date',
        'altura_peso',
        'rotina',
        'indicacao',
        'profissao',
        'objetivo',
        'acompanhamento_anterior',

        // Alimentação/nutrição
        'refeicoes_por_dia',
        'quantas_vezes_pode_comer',
        'agua_por_dia',
        'horario_fome',
        'cafe_manha',
        'almoco',
        'entre_almoco_jantar',
        'jantar',
        'beliscar',
        'mais_alguma_ref',
        'alimento_beliscar',
        'alimentos_dia_dia',
        'nao_alimentos_dia_dia',
        'refeicao_pratica',
        'balanca',
        'airfryer_bolsa',
        'alergia',
        'bebida_alcoolica',

        // Comportamento e peso
        'peso_1ano_3anos',
        'doce_salgados',
        'relacao_comida',
        'fazcoco',
        'horas_sono',

        // Exercícios
        'nivel_treino',
        'dias_treino',
        'divisao_treino',

        // Academia
        'aparelhos_academia',

        // Saúde
        'plano_saude',
        'nivel_saude',
        'tem_medico',
        'fumante',
        'medicacoes',
        'doencas_gastrointestinal',
        'doencas_cardiovascular',
        'doencas_osseas',
        'doenca_autoimune',
        'doenca_respiratoria',
        'doenca_neurologico'
    ];
}
