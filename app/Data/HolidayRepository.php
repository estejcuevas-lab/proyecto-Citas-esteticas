<?php

/**
 * AUTORES: Erick Cuevas- Camilo Ramirez
 * MATERIA: Arquitectura y Diseno de Software
 */

namespace App\Data;

use App\Models\Holiday;
use Illuminate\Database\Eloquent\Collection;

class HolidayRepository
{
    // ======================================================================
    // GUIA 6 - ACTIVIDAD 3: COMPONENTE DE ACCESO
    // Este repositorio concentra el acceso a datos de festivos desde la capa app/Data.
    // ======================================================================
    public function allForYear(?int $year = null): Collection
    {
        return Holiday::query()
            ->when($year, fn ($query) => $query->whereYear('holiday_date', $year))
            ->orderBy('holiday_date')
            ->get();
    }

    public function existsOnDate(string $date, string $countryCode = 'CO'): bool
    {
        return Holiday::query()
            ->whereDate('holiday_date', $date)
            ->where('country_code', $countryCode)
            ->exists();
    }

    public function syncMany(iterable $holidays): void
    {
        // ======================================================================
        // GUIA 7 - ACTIVIDAD 2: SINCRONIZACION DE DATOS
        // Aqui se mapean respuestas JSON externas y se sincronizan con la base de datos local.
        // ======================================================================
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
    }
}
