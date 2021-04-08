<?php

namespace App\Modules\Api\V1\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StorageType extends Model
{
    use HasFactory;

    protected $table = 'storage_type';

    protected $fillable = ['name'];
}
