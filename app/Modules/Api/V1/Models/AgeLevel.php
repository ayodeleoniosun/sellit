<?php

namespace App\Modules\Api\V1\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgeLevel extends Model
{
    use HasFactory;

    protected $table = 'age_level';

    protected $fillable = ['name'];
}
