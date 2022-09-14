<?php

namespace App\Services\Interfaces;

use App\Http\Resources\CategoryResource;

interface CategoryServiceInterface
{
    public function store(array $data): CategoryResource;

    public function update(array $data): CategoryResource;
}
