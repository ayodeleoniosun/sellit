<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function buyer()
    {
        return $this->belongsTo(User::class);
    }

    public function ads()
    {
        return $this->belongsTo(Ads::class);
    }
}
