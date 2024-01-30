<?php

namespace Database\Seeders;

use App\Models\Modulo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ModuloSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Modulo::create([
            'titulo' => 'Treinamento',
            'descricao' => 'Treinamento',
            'ordem' => 1,
        ]);

        Modulo::create([
            'titulo' => 'Dieta',
            'descricao' => 'Dieta',
            'ordem' => 2,
        ]);

        Modulo::create([
            'titulo' => 'Mental',
            'descricao' => 'Mental',
            'ordem' => 3,
        ]);
    }
}
