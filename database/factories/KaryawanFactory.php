<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class KaryawanFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'nama' => $this->faker->name(),
            'nik' => $this->faker->unique()->numerify('##########'),
            'jabatan' => $this->faker->jobTitle(),
            'foto_wajah' => 'wajah_karyawan/dummy.jpg',
            'face_token' => 'dummy_token',
        ];
    }
}
