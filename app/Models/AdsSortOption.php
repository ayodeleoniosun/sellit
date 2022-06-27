<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdsSortOption extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function ads(): BelongsTo
    {
        return $this->belongsTo(Ads::class);
    }

    public function sortOption(): BelongsTo
    {
        return $this->belongsTo(SortOption::class);
    }
}
