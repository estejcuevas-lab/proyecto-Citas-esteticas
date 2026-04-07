<?php

/**
 * AUTORES: Erick Cuevas- Camilo Ramirez
 * MATERIA: Arquitectura y Diseno de Software
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    use HasFactory;

    // ======================================================================
    // GUIA 1 - ACTIVIDAD 2: MODELO CONCEPTUAL
    // Cada servicio define la oferta del negocio y su duracion se usa en la agenda.
    // ======================================================================
    protected $fillable = [
        'business_id',
        'name',
        'description',
        'duration_minutes',
        'price',
        'active',
    ];

    public function business(): BelongsTo
    {
        // ======================================================================
        // GUIA 1 - ACTIVIDAD 2: MODELO CONCEPTUAL
        // La relacion conecta el servicio con el negocio al que pertenece.
        // ======================================================================
        return $this->belongsTo(Business::class);
    }

    public function appointments(): HasMany
    {
        // ======================================================================
        // GUIA 1 - ACTIVIDAD 2: MODELO CONCEPTUAL
        // La relacion permite enlazar el servicio con las citas que lo consumen.
        // ======================================================================
        return $this->hasMany(Appointment::class);
    }
}
