<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Business;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;

class AppointmentSeeder extends Seeder
{
    public function run(): void
    {
        $clients = User::query()->where('role', User::ROLE_CLIENT)->get();

        Business::with('services')->get()->each(function (Business $business) use ($clients): void {
            $services = $business->services;

            if ($services->isEmpty() || $clients->isEmpty()) {
                return;
            }

            foreach ($services->take(2) as $index => $service) {
                $client = $clients->random();
                $startTime = $index === 0 ? '09:00' : '11:00';
                $endTime = \Carbon\Carbon::createFromFormat('H:i', $startTime)
                    ->addMinutes($service->duration_minutes)
                    ->format('H:i');

                Appointment::create([
                    'user_id' => $client->id,
                    'business_id' => $business->id,
                    'service_id' => $service->id,
                    'appointment_date' => now()->addDays($index + 1)->toDateString(),
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'service_price' => $service->price,
                    'advance_percentage' => 50,
                    'advance_amount' => round($service->price * 0.5, 2),
                    'payment_status' => Appointment::PAYMENT_STATUS_PENDING_ADVANCE,
                    'status' => Appointment::STATUS_PENDING,
                    'notes' => 'Cita generada por seeder para pruebas funcionales.',
                ]);
            }
        });
    }
}
