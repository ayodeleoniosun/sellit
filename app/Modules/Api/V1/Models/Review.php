<?php

namespace App\Modules\Api\V1\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $table = 'review';

    public function buyer()
    {
        return $this->belongsTo(User::class);
    }

    public function ads()
    {
        return $this->belongsTo(Ads::class);
    }
}
