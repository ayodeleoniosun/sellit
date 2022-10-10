<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SortOption extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function sortOptionValues(): HasMany
    {
        return $this->hasMany(SortOptionValues::class);
    }
}
