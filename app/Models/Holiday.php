<?php

/**
 * AUTORES: Erick Cuevas- Camilo Ramirez
 * MATERIA: Arquitectura y Diseno de Software
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    use HasFactory;

    // ======================================================================
    // GUIA 6 - ACTIVIDAD 2: CLASES ENTIDAD
    // Esta entidad representa los festivos sincronizados y persistidos en la base de datos local.
    // ======================================================================
    protected $fillable = [
        'holiday_date',
        'name',
        'country_code',
        'source',
    ];

    protected function casts(): array
    {
        return [
            'holiday_date' => 'date',
        ];
    }
}
