<?php

namespace App\Modules\Api\V1\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategorySortOption extends Model
{
    use HasFactory;

    protected $table = 'sub_category_sort_option';
    
    protected $fillable = ['sub_category_id', 'sort_option_id'];
}
