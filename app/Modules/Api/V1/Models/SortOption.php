<?php

namespace App\Modules\Api\V1\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SortOption extends Model
{
    use HasFactory;

    protected $table = 'sort_option';
    
    protected $fillable = ['name'];
}
