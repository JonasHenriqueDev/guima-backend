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
            $table->timestamps();
            $table->string('name');
            $table->date('birth_date');
            $table->string('cpf')->unique()->nullable(false);
            $table->string('address');
            $table->string('email');
            $table->string('plano');
            $table->date('vencimento');
            $table->string('photo_reference')->nullable(true);
            $table->string('idade');
            $table->string('peso');
            $table->string('altura');
            $table->string('objetivo');
            $table->boolean('is_aprovada')->default(false);
            $table->json('campos_reprovados')->nullable(true);
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
