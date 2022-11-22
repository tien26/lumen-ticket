<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i < 6; $i++) {
            User::create([
                'name' => 'customer',
                'email' => 'customer@gmail.com',
                'password' => password_hash('12345', PASSWORD_BCRYPT)
            ]);
        }
    }
}
