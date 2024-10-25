<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'ian', // Substitua pelo nome desejado
            'email' => 'johndoe@example.com', // Substitua pelo email desejado
            'password' => Hash::make('senha123'), // Substitua pela senha desejada
            'matricula' => '123456' // Substitua pela matrícula desejada
        ]);
    }
}
