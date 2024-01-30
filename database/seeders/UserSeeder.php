<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Str;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $faker = Faker::create("pt_BR");

        // foreach(range(1, 15) as $index) {
        //     User::create([
        //         'name' => $faker->name(),
        //         'email' => $faker->unique()->safeEmail(),
        //         'email_verified_at' => now(),
        //         'password' => Hash::make('password'),
        //         'remember_token' => Str::random(10),
        //         'birth_date' => $faker->date(),
        //         'cpf' => $faker->unique()->cpf(),
        //         'address' => $faker->address(),
        //         'profile_type' => $faker->randomElement(['professor', 'aluno']),
        //         'profile_id' => $faker->numberBetween(1, 50),
        //     ]);
        // }

        User::create([
            'name' => 'Administrador',
            'email' => 'admin@admin.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'birth_date' => '1990-01-01',
            'cpf' => 'null',
            'address' => 'null',
            'profile_type' => 'Professor',
            'profile_id' => 1,
        ]);
    }
}
