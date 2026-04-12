<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->admin()->create([
            'name' => 'Administrador General',
            'email' => 'admin@citasapp.com',
        ]);

        User::factory()->business()->count(3)->create();
        User::factory()->client()->count(8)->create();
    }
}
