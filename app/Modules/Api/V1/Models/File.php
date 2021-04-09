<?php

namespace App\Modules\Api\V1\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $table = 'file';
    protected $fillable = ['filename'];

    const MAX_FILESIZE = 5000;
    const USER_FILE_TYPE = 'user';
    const CATEGORY_FILE_TYPE = 'category';
    const ADS_FILE_TYPE = 'ads';
    const DEFAULT_ID = 1;
}
