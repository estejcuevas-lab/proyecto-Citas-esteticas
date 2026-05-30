<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class BusinessReview extends Model
{
    protected $fillable = [
        'business_id',
        'user_id',
        'rating',
        'title',
        'comment',
        'images',
    ];

    protected $casts = [
        'images' => 'array',
    ];

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function imagePaths(): array
    {
        $images = is_array($this->images) ? $this->images : [];

        return array_values(array_filter($images, static fn ($path): bool => is_string($path) && $path !== ''));
    }

    public function imageUrls(): array
    {
        return array_map(
            static fn (string $path): string => Storage::url($path),
            $this->imagePaths()
        );
    }
}

