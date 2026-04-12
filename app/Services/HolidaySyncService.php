<?php

namespace App\Services;

use App\Models\Holiday;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class HolidaySyncService
{
    public function sync(int $year, string $countryCode = 'CO'): array
    {
        try {
            $response = Http::baseUrl('https://date.nager.at/api/v3')
                ->timeout(10)
                ->acceptJson()
                ->get("/PublicHolidays/{$year}/{$countryCode}");
        } catch (ConnectionException $exception) {
            throw new RuntimeException('No fue posible conectar con el servicio externo de festivos.', 0, $exception);
        }

        if ($response->failed()) {
            throw new RuntimeException('El servicio externo de festivos respondio con un error.');
        }

        $holidays = collect($response->json())
            ->map(fn (array $holiday) => [
                'holiday_date' => $holiday['date'],
                'name' => $holiday['localName'] ?? $holiday['name'],
                'country_code' => $countryCode,
                'source' => 'nager_date',
            ]);

        foreach ($holidays as $holiday) {
            Holiday::updateOrCreate(
                [
                    'holiday_date' => $holiday['holiday_date'],
                    'country_code' => $holiday['country_code'],
                ],
                [
                    'name' => $holiday['name'],
                    'source' => $holiday['source'],
                ]
            );
        }

        return [
            'count' => $holidays->count(),
            'year' => $year,
            'country_code' => $countryCode,
        ];
    }
}
