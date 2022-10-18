<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SortOptionValues extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function sortOption(): BelongsTo
    {
        return $this->belongsTo(SortOption::class);
    }
}
