<?php

namespace Database\Seeders;

use App\Models\Business;
use App\Models\BusinessHour;
use Illuminate\Database\Seeder;

class BusinessHourSeeder extends Seeder
{
    public function run(): void
    {
        Business::all()->each(function (Business $business): void {
            foreach (range(1, 6) as $day) {
                BusinessHour::factory()->create([
                    'business_id' => $business->id,
                    'day_of_week' => $day,
                ]);
            }
        });
    }
}
