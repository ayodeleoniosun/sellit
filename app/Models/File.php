<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class File extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    const MAX_FILESIZE = 5000;
    const USER_FILE_TYPE = 'user';
    const CATEGORY_FILE_TYPE = 'category';
    const ADS_FILE_TYPE = 'ads';
    const DEFAULT_ID = 1;
}
