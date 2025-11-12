<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class admintest extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
                User::create([
            'name' => 'Admin Test',
            'email' => 'admintest@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);
    }
}
