<?php

namespace App\Contracts\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface BaseRepositoryInterface
{
    public function all(array $relations = [], string $orderBy = 'desc'): Collection;

    public function create(array $attributes): Model;

    public function find(int $id, array $relations = []): ?Model;

    public function insert(array $attributes): bool;

    public function insertGetId(array $attributes): int;

    public function update(int $id, array $attributes, array $relations = []): Model;

    public function delete(): void;
}
