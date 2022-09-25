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

    public function getRouteKeyName(): string
    {
        return $this->slug;
    }

    public function sortOptions(): HasMany
    {
        return $this->hasMany(AdsSortOption::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class)->select('id', 'name', 'slug');
    }

    public function subCategory(): BelongsTo
    {
        return $this->belongsTo(SubCategory::class)->select('id', 'category_id', 'name', 'slug');
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class)->select('id', 'first_name', 'last_name', 'slug', 'email', 'phone');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function pictures(): HasMany
    {
        return $this->hasMany(AdsPicture::class);
    }
}

