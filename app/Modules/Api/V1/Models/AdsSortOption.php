<?php

namespace App\Modules\Api\V1\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdsSortOption extends Model
{
    use HasFactory;

    protected $table = 'ads_sort_option';

    protected $fillable = ['ads_id', 'sort_option_id', 'value'];
}
