<?php

namespace Database\Seeders;

use App\Models\VideoAula;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VideoAulaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //videoaulas do submodulo treino de peito

        VideoAula::create([
            'titulo' => 'Supino Reto',
            'descricao' => 'Supino Reto',
            'url_id' => '1NvVUWJlY1s',
            'ordem' => 1,
            'submodulo_id' => 2,
        ]);

        VideoAula::create([
            'titulo' => 'Supino Inclinado',
            'descricao' => 'Supino Inclinado',
            'url_id' => '1NvVUWJlY1s',
            'ordem' => 2,
            'submodulo_id' => 2,
        ]);

        VideoAula::create([
            'titulo' => 'Supino Declinado',
            'descricao' => 'Supino Declinado',
            'url_id' => '1NvVUWJlY1s',
            'ordem' => 3,
            'submodulo_id' => 2,
        ]);

        VideoAula::create([
            'titulo' => 'Crucifixo Reto',
            'descricao' => 'Crucifixo Reto',
            'url_id' => '1NvVUWJlY1s',
            'ordem' => 4,
            'submodulo_id' => 2,
        ]);
    }
}
