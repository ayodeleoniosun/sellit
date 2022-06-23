<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ads extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function sortOptions(): HasMany
    {
        return $this->hasMany(AdsSortOption::class);
    }

    public function categories(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function subCategories(): BelongsTo
    {
        return $this->belongsTo(SubCategory::class);
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
