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
use Illuminate\Support\Facades\Storage;

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
        'gallery_images',
    ];

    protected $casts = [
        'active' => 'boolean',
        'gallery_images' => 'array',
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

    public function galleryImagePaths(): array
    {
        $images = is_array($this->gallery_images) ? $this->gallery_images : [];

        return array_values(array_filter($images, static fn ($path): bool => is_string($path) && $path !== ''));
    }

    public function galleryImageUrls(): array
    {
        return array_map(
            static fn (string $path): string => Storage::url($path),
            $this->galleryImagePaths()
        );
    }
}
