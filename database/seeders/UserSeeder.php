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
            'name' => 'ian',
            'email' => 'johndoe@example.com',
            'password' => Hash::make('senha123'),
            'matricula' => '123456' 
        ]);

        User::create([
            'name' => 'Max', 
            'email' => 'max@example.com', 
            'password' => Hash::make('senha123'), 
            'matricula' => '654321' 
        ]);
    }
}
