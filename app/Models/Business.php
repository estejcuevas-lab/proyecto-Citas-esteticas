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

class Business extends Model
{
    use HasFactory;

    // ======================================================================
    // GUIA 1 - ACTIVIDAD 1: ESCENARIO DE NEGOCIO
    // Esta entidad representa cada negocio que administra servicios y citas dentro del sistema.
    // ======================================================================
    protected $fillable = [
        'name',
        'type',
        'phone',
        'email',
        'address',
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function services(): HasMany
    {
        // ======================================================================
        // GUIA 1 - ACTIVIDAD 2: MODELO CONCEPTUAL
        // La relacion muestra que un negocio agrupa varios servicios dentro del dominio.
        // ======================================================================
        return $this->hasMany(Service::class);
    }

    public function appointments(): HasMany
    {
        // ======================================================================
        // GUIA 1 - ACTIVIDAD 2: MODELO CONCEPTUAL
        // La relacion muestra que un negocio administra multiples citas del sistema.
        // ======================================================================
        return $this->hasMany(Appointment::class);
    }

    public function hours(): HasMany
    {
        // ======================================================================
        // GUIA 1 - ACTIVIDAD 2: MODELO CONCEPTUAL
        // La relacion separa la configuracion de horarios del registro concreto de citas.
        // ======================================================================
        return $this->hasMany(BusinessHour::class);
    }
}
