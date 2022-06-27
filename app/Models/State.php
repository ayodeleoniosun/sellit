<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class State extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function userProfiles(): HasMany
    {
        return $this->hasMany(UserProfile::class);
    }

    public function cities(): HasMany
    {
        return $this->hasMany(City::class);
    }
}
