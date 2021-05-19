<?php

namespace App\Modules\Api\V1\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ads extends Model
{
    use HasFactory;

    protected $table = 'ads';

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
