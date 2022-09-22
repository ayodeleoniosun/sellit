<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\MustVerifyEmail as MustVerifyEmailTrait;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Model implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract, MustVerifyEmail
{
    use HasFactory,
        HasApiTokens,
        Authenticatable,
        Authorizable,
        CanResetPassword,
        Notifiable,
        SoftDeletes,
        MustVerifyEmailTrait;

    protected $guarded = ['id'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function isAdmin(): bool
    {
        return $this->role === 'admin' && $this->isVerified();
    }

    public function isVerified(): bool
    {
        return $this->hasVerifiedEmail();
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function fullName(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => ucwords($attributes['first_name'] . " " . $attributes['last_name'])
        );
    }

    public function profile(): HasOne
    {
        return $this->hasOne(UserProfile::class);
    }

    public function picture(): HasOne
    {
        return $this->hasOne(UserProfilePicture::class)->latest();
    }

    public function pictures(): HasMany
    {
        return $this->hasMany(UserProfilePicture::class);
    }

    public function ads(): HasMany
    {
        return $this->hasMany(Ads::class, 'seller_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'buyer_id');
    }

    public function businessProfile(): HasOne
    {
        return $this->hasOne(BusinessProfile::class);
    }
}
