<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    use HasFactory;

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
        return $this->belongsTo(Business::class);
    }
    public function appointments(): HasMany
{
    return $this->hasMany(Appointment::class);
}
}