<?php

namespace App\Modules\Api\V1\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;

class User extends Authenticable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $table = 'user';

    protected $hidden = [
        'password',
        'token_expires_at'
    ];

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
}
