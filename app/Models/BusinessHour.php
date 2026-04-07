<?php

/**
 * AUTORES: Erick Cuevas- Camilo Ramirez
 * MATERIA: Arquitectura y Diseno de Software
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BusinessHour extends Model
{
    use HasFactory;

    // ======================================================================
    // GUIA 1 - ACTIVIDAD 2: MODELO CONCEPTUAL
    // Esta entidad separa la configuracion operativa del negocio de la reserva concreta.
    // ======================================================================
    protected $fillable = [
        'business_id',
        'day_of_week',
        'opens_at',
        'closes_at',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function business(): BelongsTo
    {
        // ======================================================================
        // GUIA 1 - ACTIVIDAD 2: MODELO CONCEPTUAL
        // El horario se asocia a un negocio especifico dentro del modelo del dominio.
        // ======================================================================
        return $this->belongsTo(Business::class);
    }

    public static function dayOptions(): array
    {
        return [
            0 => 'Domingo',
            1 => 'Lunes',
            2 => 'Martes',
            3 => 'Miercoles',
            4 => 'Jueves',
            5 => 'Viernes',
            6 => 'Sabado',
        ];
    }
}
