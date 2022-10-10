<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubCategorySortOptionValue extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function subCategory(): BelongsTo
    {
        return $this->belongsTo(SubCategory::class);
    }

    public function subCategorySortOption(): BelongsTo
    {
        return $this->belongsTo(SubCategorySortOption::class);
    }

    public function sortOptionValues(): BelongsTo
    {
        return $this->belongsTo();
    }

}
