<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\Business;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Appointment>
 */
class AppointmentFactory extends Factory
{
    protected $model = Appointment::class;

    public function definition(): array
    {
        $service = Service::factory()->create();

        return [
            'user_id' => User::factory()->client(),
            'business_id' => $service->business_id,
            'service_id' => $service->id,
            'appointment_date' => fake()->dateTimeBetween('+1 day', '+15 days')->format('Y-m-d'),
            'start_time' => '09:00',
            'end_time' => '10:00',
            'service_price' => $service->price,
            'advance_percentage' => 50,
            'advance_amount' => round($service->price * 0.5, 2),
            'payment_status' => Appointment::PAYMENT_STATUS_PENDING_ADVANCE,
            'status' => Appointment::STATUS_PENDING,
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
