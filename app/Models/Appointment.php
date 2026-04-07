<?php

/**
 * AUTORES: Erick Cuevas- Camilo Ramirez
 * MATERIA: Arquitectura y Diseno de Software
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appointment extends Model
{
    use HasFactory;

    // ======================================================================
    // GUIA 1 - ACTIVIDAD 1: ESCENARIO DE NEGOCIO
    // La cita conecta cliente, negocio y servicio dentro del flujo central del sistema.
    // ======================================================================
    public const STATUS_PENDING = 'pending';
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_COMPLETED = 'completed';

    protected $fillable = [
        'user_id',
        'business_id',
        'service_id',
        'appointment_date',
        'start_time',
        'end_time',
        'status',
        'notes',
    ];

    public function user(): BelongsTo
    {
        // ======================================================================
        // GUIA 1 - ACTIVIDAD 2: MODELO CONCEPTUAL
        // La cita conserva la referencia al usuario que solicita o administra la reserva.
        // ======================================================================
        return $this->belongsTo(User::class);
    }

    public function business(): BelongsTo
    {
        // ======================================================================
        // GUIA 1 - ACTIVIDAD 2: MODELO CONCEPTUAL
        // La cita mantiene el enlace con el negocio donde se prestara el servicio.
        // ======================================================================
        return $this->belongsTo(Business::class);
    }

    public function service(): BelongsTo
    {
        // ======================================================================
        // GUIA 1 - ACTIVIDAD 2: MODELO CONCEPTUAL
        // La cita identifica el servicio reservado dentro del negocio.
        // ======================================================================
        return $this->belongsTo(Service::class);
    }

    public static function statuses(): array
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_CONFIRMED,
            self::STATUS_CANCELLED,
            self::STATUS_COMPLETED,
        ];
    }

    protected function casts(): array
    {
        return [
            'appointment_date' => 'date',
        ];
    }
}
