<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\MustVerifyEmail as MustVerifyEmailTrait;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;

class User extends Model implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract, MustVerifyEmail
{
    use HasFactory,
        Authenticatable,
        Authorizable,
        CanResetPassword,
        Notifiable,
        SoftDeletes,
        MustVerifyEmailTrait;

    protected array $guarded = ['id'];

    protected array $hidden = [
        'password',
        'remember_token',
    ];

    public function fullname()
    {
        return ucwords(sprintf('%s %s', $this->first_name, $this->last_name));
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function picture()
    {
        return $this->belongsTo(File::class, 'file_id');
    }

    public function ads()
    {
        return $this->hasMany(Ads::class, 'seller_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'seller_id');
    }

    public function reviewed()
    {
        return $this->hasMany(Review::class, 'buyer_id');
    }

    public static function getUserByEmail($email)
    {
        return self::where('email_address', $email)->first();
    }
}
