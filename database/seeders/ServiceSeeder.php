<?php

namespace Database\Seeders;

use App\Models\Business;
use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        Business::all()->each(function (Business $business): void {
            Service::factory()->count(3)->create([
                'business_id' => $business->id,
            ]);
        });
    }
}
