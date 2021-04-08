<?php

namespace App\Modules\Api\V1\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Furnishing extends Model
{
    use HasFactory;

    protected $table = 'furnishing';

    protected $fillable = ['name'];
}