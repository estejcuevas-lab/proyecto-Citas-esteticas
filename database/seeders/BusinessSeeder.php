<?php

namespace Database\Seeders;

use App\Models\Business;
use App\Models\User;
use Illuminate\Database\Seeder;

class BusinessSeeder extends Seeder
{
    public function run(): void
    {
        User::query()
            ->where('role', User::ROLE_BUSINESS)
            ->get()
            ->each(function (User $user): void {
                Business::factory()->create([
                    'user_id' => $user->id,
                    'email' => $user->email,
                ]);
            });
    }
}
