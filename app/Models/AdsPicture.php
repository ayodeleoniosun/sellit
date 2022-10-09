<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdsPicture extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function ads(): BelongsTo
    {
        return $this->belongsTo(Ads::class);
    }

    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class);
    }
}
