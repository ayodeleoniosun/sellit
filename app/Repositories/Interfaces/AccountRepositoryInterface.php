<?php

namespace App\Repositories\Interfaces;

use App\Models\User;

interface AccountRepositoryInterface
{
    public function store(array $data): User;
}
