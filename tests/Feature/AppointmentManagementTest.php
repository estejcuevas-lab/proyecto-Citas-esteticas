<?php

/**
 * AUTORES: Erick Cuevas- Camilo Ramirez
 * MATERIA: Arquitectura y Diseno de Software
 */

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Business;
use App\Models\BusinessHour;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppointmentManagementTest extends TestCase
{
    use RefreshDatabase;

    // ======================================================================
    // GUIA 6 - ACTIVIDAD 4: CASO TESTIGO
    // Esta prueba verifica el flujo completo desde la entrada del formulario hasta la persistencia de la cita.
    // ======================================================================
    public function test_client_can_create_appointment_with_automatic_end_time_and_redirect_to_payment(): void
    {
        $client = User::factory()->client()->create();
        $owner = User::factory()->business()->create();
        $business = Business::create([
            'name' => 'Estetica Viva',
            'type' => 'estetica',
            'user_id' => $owner->id,
        ]);

        BusinessHour::create([
            'business_id' => $business->id,
            'day_of_week' => 1,
            'opens_at' => '08:00',
            'closes_at' => '18:00',
            'is_active' => true,
        ]);

        $service = Service::create([
            'business_id' => $business->id,
            'name' => 'Corte Premium',
            'duration_minutes' => 60,
            'price' => 100000,
            'active' => true,
        ]);

        $response = $this->actingAs($client)->post('/appointments', [
            'business_id' => $business->id,
            'service_id' => $service->id,
            'appointment_date' => '2026-04-13',
            'start_time' => '09:00',
            'status' => Appointment::STATUS_PENDING,
            'payment_status' => Appointment::PAYMENT_STATUS_PENDING_ADVANCE,
            'notes' => 'Primera cita',
        ]);

        $appointment = Appointment::query()->latest('id')->first();

        $response->assertRedirect("/appointments/{$appointment->id}/payment");
        $this->assertDatabaseHas('appointments', [
            'user_id' => $client->id,
            'business_id' => $business->id,
            'service_id' => $service->id,
            'start_time' => '09:00',
            'end_time' => '10:00',
            'service_price' => 100000.00,
            'advance_amount' => 50000.00,
            'payment_status' => Appointment::PAYMENT_STATUS_PENDING_ADVANCE,
        ]);
    }

    public function test_client_can_pay_advance_and_auto_confirm_the_appointment(): void
    {
        $client = User::factory()->client()->create();
        $owner = User::factory()->business()->create();
        $business = Business::create([
            'name' => 'Glow Studio',
            'type' => 'estetica',
            'user_id' => $owner->id,
        ]);

        $service = Service::create([
            'business_id' => $business->id,
            'name' => 'Limpieza facial',
            'duration_minutes' => 60,
            'price' => 120000,
            'active' => true,
        ]);

        $appointment = Appointment::create([
            'user_id' => $client->id,
            'business_id' => $business->id,
            'service_id' => $service->id,
            'appointment_date' => '2026-04-13',
            'start_time' => '11:00',
            'end_time' => '12:00',
            'service_price' => 120000,
            'advance_percentage' => 50,
            'advance_amount' => 60000,
            'payment_status' => Appointment::PAYMENT_STATUS_PENDING_ADVANCE,
            'status' => Appointment::STATUS_PENDING,
        ]);

        $response = $this->actingAs($client)->post("/appointments/{$appointment->id}/payment", [
            'payment_method' => 'nequi',
            'account_holder' => $client->name,
            'reference' => 'NEQ-12345',
            'phone_number' => '3001234567',
        ]);

        $response->assertRedirect("/appointments/{$appointment->id}/edit");
        $this->assertDatabaseHas('appointments', [
            'id' => $appointment->id,
            'payment_status' => Appointment::PAYMENT_STATUS_PAID,
            'status' => Appointment::STATUS_CONFIRMED,
        ]);
    }

    public function test_appointment_cannot_overlap_another_appointment(): void
    {
        $client = User::factory()->client()->create();
        $owner = User::factory()->business()->create();
        $business = Business::create([
            'name' => 'Barberia Norte',
            'type' => 'barberia',
            'user_id' => $owner->id,
        ]);

        BusinessHour::create([
            'business_id' => $business->id,
            'day_of_week' => 1,
            'opens_at' => '08:00',
            'closes_at' => '18:00',
            'is_active' => true,
        ]);

        $service = Service::create([
            'business_id' => $business->id,
            'name' => 'Barba',
            'duration_minutes' => 30,
            'price' => 30000,
            'active' => true,
        ]);

        Appointment::create([
            'user_id' => $client->id,
            'business_id' => $business->id,
            'service_id' => $service->id,
            'appointment_date' => '2026-04-13',
            'start_time' => '09:00',
            'end_time' => '09:30',
            'service_price' => 30000,
            'advance_percentage' => 50,
            'advance_amount' => 15000,
            'payment_status' => Appointment::PAYMENT_STATUS_PENDING_ADVANCE,
            'status' => Appointment::STATUS_CONFIRMED,
        ]);

        $response = $this->actingAs($client)
            ->from('/appointments/create')
            ->post('/appointments', [
                'business_id' => $business->id,
                'service_id' => $service->id,
                'appointment_date' => '2026-04-13',
                'start_time' => '09:15',
                'status' => Appointment::STATUS_PENDING,
                'payment_status' => Appointment::PAYMENT_STATUS_PENDING_ADVANCE,
            ]);

        $response->assertRedirect('/appointments/create');
        $response->assertSessionHasErrors('start_time');
    }

    public function test_business_user_can_mark_payment_as_paid(): void
    {
        $client = User::factory()->client()->create();
        $owner = User::factory()->business()->create();
        $business = Business::create([
            'name' => 'Consultorio Vital',
            'type' => 'consultorio',
            'user_id' => $owner->id,
        ]);

        $service = Service::create([
            'business_id' => $business->id,
            'name' => 'Consulta',
            'duration_minutes' => 45,
            'price' => 80000,
            'active' => true,
        ]);

        $appointment = Appointment::create([
            'user_id' => $client->id,
            'business_id' => $business->id,
            'service_id' => $service->id,
            'appointment_date' => '2026-04-13',
            'start_time' => '10:00',
            'end_time' => '10:45',
            'service_price' => 80000,
            'advance_percentage' => 50,
            'advance_amount' => 40000,
            'payment_status' => Appointment::PAYMENT_STATUS_PENDING_ADVANCE,
            'status' => Appointment::STATUS_CONFIRMED,
        ]);

        $response = $this->actingAs($owner)->patch("/appointments/{$appointment->id}/payment", [
            'payment_status' => Appointment::PAYMENT_STATUS_PAID,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('appointments', [
            'id' => $appointment->id,
            'payment_status' => Appointment::PAYMENT_STATUS_PAID,
        ]);
    }

    public function test_client_can_update_appointment_even_if_time_arrives_with_seconds(): void
    {
        $client = User::factory()->client()->create();
        $owner = User::factory()->business()->create();
        $business = Business::create([
            'name' => 'Studio Time',
            'type' => 'spa',
            'user_id' => $owner->id,
        ]);

        BusinessHour::create([
            'business_id' => $business->id,
            'day_of_week' => 1,
            'opens_at' => '08:00',
            'closes_at' => '18:00',
            'is_active' => true,
        ]);

        $service = Service::create([
            'business_id' => $business->id,
            'name' => 'Masaje',
            'duration_minutes' => 60,
            'price' => 90000,
            'active' => true,
        ]);

        $appointment = Appointment::create([
            'user_id' => $client->id,
            'business_id' => $business->id,
            'service_id' => $service->id,
            'appointment_date' => '2026-04-13',
            'start_time' => '10:00:00',
            'end_time' => '11:00:00',
            'service_price' => 90000,
            'advance_percentage' => 50,
            'advance_amount' => 45000,
            'payment_status' => Appointment::PAYMENT_STATUS_PENDING_ADVANCE,
            'status' => Appointment::STATUS_PENDING,
        ]);

        $response = $this->actingAs($client)->put("/appointments/{$appointment->id}", [
            'business_id' => $business->id,
            'service_id' => $service->id,
            'appointment_date' => '2026-04-13',
            'start_time' => '10:30:00',
            'status' => Appointment::STATUS_PENDING,
            'payment_status' => Appointment::PAYMENT_STATUS_PENDING_ADVANCE,
            'notes' => 'Reagendada',
        ]);

        $response->assertRedirect("/appointments/{$appointment->id}/edit");
        $this->assertDatabaseHas('appointments', [
            'id' => $appointment->id,
            'start_time' => '10:30',
            'end_time' => '11:30',
            'notes' => 'Reagendada',
        ]);
    }
}
