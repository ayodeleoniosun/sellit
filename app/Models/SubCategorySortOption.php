<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubCategorySortOption extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function subCategory(): BelongsTo
    {
        return $this->belongsTo(SubCategory::class);
    }

    public function sortOption(): BelongsTo
    {
        return $this->belongsTo(SortOption::class);
    }
}
