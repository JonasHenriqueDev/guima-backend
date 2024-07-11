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
        Schema::table('alunos', function (Blueprint $table) {
            // Adicionar a coluna anamnese_id
            $table->unsignedBigInteger('anamnese_id')->nullable();

            // Definir a chave estrangeira (foreign key) para a tabela anamneses
            $table->foreign('anamnese_id')->references('id')->on('anamneses')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alunos', function (Blueprint $table) {
            // Remover a chave estrangeira e a coluna anamnese_id
            $table->dropForeign(['anamnese_id']);
            $table->dropColumn('anamnese_id');
        });
    }
};
