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
        Schema::table('submodulos', function (Blueprint $table) {
            $table->dropForeign(['modulo_id']);
            $table->foreignId('modulo_id')
                ->constrained('modulos')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('submodulos', function (Blueprint $table) {
            $table->dropForeign(['modulo_id']);
            $table->foreignId('modulo_id')
                ->constrained('modulos');
        });
    }
};
