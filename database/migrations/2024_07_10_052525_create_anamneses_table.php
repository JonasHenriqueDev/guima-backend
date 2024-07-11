<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('anamneses', function (Blueprint $table) {
            $table->id();
            //status
            $table->boolean('is_aprovada')->default(false);

            // Dados pessoais
            $table->string('name');
            $table->string('cpf');
            $table->string('email')->unique();
            $table->string('address');
            $table->string('telefone');
            $table->string('plano')->nullable();
            $table->string('vencimento')->nullable();
            $table->date('birth_date');
            $table->string('altura_peso');
            $table->text('rotina');
            $table->string('indicacao');
            $table->string('profissao');
            $table->string('objetivo')->nullable();
            $table->string('acompanhamento_anterior')->nullable();

            // Alimentação/nutrição
            $table->string('refeicoes_por_dia');
            $table->string('quantas_vezes_pode_comer');
            $table->string('agua_por_dia');
            $table->string('horario_fome');
            $table->string('cafe_manha');
            $table->string('almoco');
            $table->string('entre_almoco_jantar');
            $table->string('jantar');
            $table->string('beliscar');
            $table->string('mais_alguma_ref')->nullable();
            $table->string('alimento_beliscar');
            $table->string('alimentos_dia_dia');
            $table->string('nao_alimentos_dia_dia');
            $table->string('refeicao_pratica');
            $table->string('balanca');
            $table->string('airfryer_bolsa');
            $table->string('alergia');
            $table->string('bebida_alcoolica');

            // Comportamento e peso
            $table->string('peso_1ano_3anos');
            $table->string('doce_salgados');
            $table->string('relacao_comida');
            $table->string('fazcoco');
            $table->string('horas_sono');

            // Exercícios
            $table->string('nivel_treino');
            $table->string('dias_treino');
            $table->string('divisao_treino');

            // Academia
            $table->string('aparelhos_academia');

            // Saúde
            $table->string('plano_saude');
            $table->string('nivel_saude');
            $table->string('tem_medico');
            $table->string('fumante');
            $table->string('medicacoes');
            $table->string('doencas_gastrointestinal');
            $table->string('doencas_cardiovascular');
            $table->string('doencas_osseas');
            $table->string('doenca_autoimune');
            $table->string('doenca_respiratoria');
            $table->string('doenca_neurologico');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anamneses');
    }
};
