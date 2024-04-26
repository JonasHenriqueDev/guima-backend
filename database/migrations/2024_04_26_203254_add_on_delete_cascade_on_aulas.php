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
        Schema::table('aulas', function (Blueprint $table) {
            $table->dropForeign(['submodulo_id']);
            $table->foreign('submodulo_id')
                ->references('id')
                ->on('submodulos')
                ->onDelete('cascade'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('aulas', function (Blueprint $table) {
            $table->dropForeign(['submodulo_id']);
        });
    }
};
