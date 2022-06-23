<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function subCategories()
    {
        return $this->hasMany(SubCategory::class);
    }

    public function ads()
    {
        return $this->hasMany(Ads::class);
    }
}
