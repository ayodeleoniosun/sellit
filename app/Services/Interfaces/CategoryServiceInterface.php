<?php

namespace App\Services\Interfaces;

use App\Http\Resources\CategoryResource;
use Illuminate\Http\Request;

interface CategoryServiceInterface
{
    public function store(Request $request): CategoryResource;
}
