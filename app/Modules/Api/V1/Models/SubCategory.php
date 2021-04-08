<?php

namespace App\Modules\Api\V1\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Api\V1\Resources\SubCategorySortOptionResource;

class SubCategory extends Model
{
    use HasFactory;

    protected $table = 'sub_category';

    protected $fillable = ['category_id', 'name'];

    public function sortOptions()
    {
        return $this->hasMany(SubCategorySortOption::class);
    }
}