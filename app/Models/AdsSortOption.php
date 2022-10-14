<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdsSortOption extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function ads(): BelongsTo
    {
        return $this->belongsTo(Ads::class);
    }

    public function sortOptionValue(): BelongsTo
    {
        return $this->belongsTo(SortOptionValues::class);
    }
}
