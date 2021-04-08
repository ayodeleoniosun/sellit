<?php

namespace App\Modules\Api\V1\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperatingSystem extends Model
{
    use HasFactory;

    protected $table = 'operating_system';

    protected $fillable = ['name'];
}
