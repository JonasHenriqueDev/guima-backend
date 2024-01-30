<?php

namespace Database\Seeders;

use App\Models\Submodulo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubmoduloSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Submodulo::create([
            'titulo' => 'Preparação',
            'descricao' => 'Preparação',
            'ordem' => 1,
            'modulo_id' => 1,
        ]);

        Submodulo::create([
            'titulo' => 'Treino de Peito',
            'descricao' => 'Treino de Peito',
            'ordem' => 2,
            'modulo_id' => 1,
        ]);

        Submodulo::create([
            'titulo' => 'Treino de Costas',
            'descricao' => 'Treino de Costas',
            'ordem' => 3,
            'modulo_id' => 1,
        ]);
    }
}
