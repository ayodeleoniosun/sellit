<?php

namespace App\Modules\Api\V1\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $table = 'city';
    protected $fillable = ['state_id', 'name'];
    
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
