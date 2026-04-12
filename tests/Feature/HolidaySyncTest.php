<?php

/**
 * AUTORES: Erick Cuevas- Camilo Ramirez
 * MATERIA: Cliente-Servidor
 */

namespace Tests\Feature;

use App\Models\Holiday;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class HolidaySyncTest extends TestCase
{
    use RefreshDatabase;

    // ======================================================================
    // GUIA 7 - ACTIVIDAD 1: INTEROPERABILIDAD WEB
    // La prueba valida el consumo de un servicio HTTP externo y la respuesta JSON del API local.
    // ======================================================================
    public function test_admin_can_sync_holidays_from_external_api(): void
    {
        Http::fake([
            'https://date.nager.at/api/v3/PublicHolidays/*' => Http::response([
                [
                    'date' => '2026-07-20',
                    'localName' => 'Dia de la Independencia',
                    'name' => 'Independence Day',
                ],
                [
                    'date' => '2026-08-07',
                    'localName' => 'Batalla de Boyaca',
                    'name' => 'Battle of Boyaca',
                ],
            ], 200),
        ]);

        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->postJson('/api/holidays/sync', [
            'year' => 2026,
            'country_code' => 'CO',
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('data.count', 2);

        $this->assertDatabaseHas('holidays', [
            'holiday_date' => '2026-07-20',
            'country_code' => 'CO',
        ]);
    }

    public function test_api_returns_error_when_external_service_fails(): void
    {
        // ======================================================================
        // GUIA 7 - ACTIVIDAD 3: TRATAMIENTO DE ERRORES
        // La prueba confirma que una falla del servicio externo se traduce en una respuesta controlada.
        // ======================================================================
        Http::fake([
            'https://date.nager.at/api/v3/PublicHolidays/*' => Http::response([], 500),
        ]);

        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->postJson('/api/holidays/sync', [
            'year' => 2026,
            'country_code' => 'CO',
        ]);

        $response
            ->assertStatus(502)
            ->assertJsonPath('message', 'El servicio externo de festivos respondio con un error.');

        $this->assertDatabaseCount('holidays', 0);
    }
}
