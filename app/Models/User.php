<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public const ROLE_CLIENT = 'client';
    public const ROLE_BUSINESS = 'business';
    public const ROLE_ADMIN = 'admin';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'role',
        'password',
        'avatar',
        'auth_provider',
        'provider_id',
        'profile_completed_at',
        'business_requested_at',
        'business_approved_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'profile_completed_at' => 'datetime',
            'business_requested_at' => 'datetime',
            'business_approved_at' => 'datetime',
        ];
    }

    public function businesses(): HasMany
    {
        return $this->hasMany(Business::class);
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function businessReviews(): HasMany
    {
        return $this->hasMany(BusinessReview::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isBusiness(): bool
    {
        return $this->role === self::ROLE_BUSINESS;
    }

    public function isClient(): bool
    {
        return $this->role === self::ROLE_CLIENT;
    }

    public function hasCompletedProfile(): bool
    {
        return $this->profile_completed_at !== null;
    }

    public function isBusinessApproved(): bool
    {
        return $this->isBusiness() && $this->business_approved_at !== null;
    }

    public function hasPendingBusinessRequest(): bool
    {
        return $this->business_requested_at !== null && $this->business_approved_at === null;
    }

    public function needsProfileCompletion(): bool
    {
        return ! $this->isAdmin() && ! $this->hasCompletedProfile();
    }

    public function needsBusinessApproval(): bool
    {
        return $this->isBusiness() && ! $this->isBusinessApproved();
    }

    public function canManageBusinesses(): bool
    {
        return $this->isAdmin() || $this->isBusinessApproved();
    }
}
