<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BusinessManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_business_user_can_create_a_business(): void
    {
        $user = User::factory()->business()->create();

        $response = $this->actingAs($user)->post('/businesses', [
            'name' => 'Barberia Central',
            'type' => 'barberia',
            'phone' => '3001234567',
            'email' => 'barberia@example.com',
            'address' => 'Calle 10',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('businesses', [
            'name' => 'Barberia Central',
            'user_id' => $user->id,
        ]);
    }

    public function test_client_user_cannot_create_a_business(): void
    {
        $user = User::factory()->client()->create();

        $response = $this->actingAs($user)->post('/businesses', [
            'name' => 'Negocio Prohibido',
            'type' => 'barberia',
        ]);

        $response->assertForbidden();
        $this->assertDatabaseMissing('businesses', [
            'name' => 'Negocio Prohibido',
        ]);
    }
}
