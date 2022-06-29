<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $primaryKey = 'token';

    protected $keyType = 'string';

    const UPDATED_AT = null;

    protected $fillable = [
        'email',
        'token'
    ];
}
