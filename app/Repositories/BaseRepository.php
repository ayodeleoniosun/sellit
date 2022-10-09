<?php

namespace App\Repositories;

use App\Contracts\Repositories\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class BaseRepository implements BaseRepositoryInterface
{
    private Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function all(array $relations = [], string $orderBy = 'desc'): Collection
    {
        if ($orderBy === 'desc') {
            return $this->model->with($relations)->latest()->get();
        }

        return $this->model->with($relations)->oldest()->get();
    }

    public function create(array $attributes): Model
    {
        $model = $this->model->create($attributes);

        return $model->fresh();
    }

    public function find(int $id, array $relations = []): ?Model
    {
        return $this->model->with($relations)->find($id);
    }

    public function insert(array $attributes): bool
    {
        return $this->model->insert($attributes);
    }

    public function insertGetId(array $attributes): int
    {
        return $this->model->insertGetId($attributes);
    }

    public function update(int $id, array $attributes, array $relations = []): Model
    {
        $model = $this->model->find($id);

        $model->update($attributes);

        return $this->find($id, $relations);
    }

    public function delete(): void
    {
        $this->model->delete();
    }
}
