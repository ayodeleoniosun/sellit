<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ads extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function sortOptions()
    {
        return $this->hasMany(AdsSortOption::class);
    }

    public function categories()
    {
        return $this->belongsTo(Category::class);
    }

    public function subCategories()
    {
        return $this->belongsTo(SubCategory::class);
    }

    public function seller()
    {
        return $this->belongsTo(User::class);
    }
}
