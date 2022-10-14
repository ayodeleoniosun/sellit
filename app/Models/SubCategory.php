<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class)->select('id', 'name', 'slug');
    }

    public function allSortOptions(): HasMany
    {
        return $this->hasMany(SubCategorySortOption::class);
    }

    public function sortOptions(): BelongsToMany
    {
        return $this->belongsToMany(
            SubCategorySortOption::class,
            'sub_category_sort_options',
            'sub_category_id',
            'sort_option_id'
        )->withTimestamps();
    }

    public function ads(): HasMany
    {
        return $this->hasMany(Ads::class);
    }
}
