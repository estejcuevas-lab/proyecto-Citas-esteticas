<?php

namespace Database\Factories;

use App\Models\Business;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Service>
 */
class ServiceFactory extends Factory
{
    protected $model = Service::class;

    public function definition(): array
    {
        return [
            'business_id' => Business::factory(),
            'name' => fake()->randomElement([
                'Corte premium',
                'Manicure',
                'Masaje relajante',
                'Limpieza facial',
            ]),
            'description' => fake()->sentence(),
            'duration_minutes' => fake()->randomElement([30, 45, 60, 90]),
            'price' => fake()->randomFloat(2, 25000, 180000),
            'active' => true,
        ];
    }
}
