<?php

namespace App\Repositories\Elouquent;

use Illuminate\Database\Eloquent\Model;
use App\Repositories\Contracts\RepositoryInterface;

abstract class AbstractRepository implements RepositoryInterface
{
    protected $model;

    public function __construct()
    {
        $this->model = $this->resolveModel();
    }

    protected function resolveModel()
    {
        return app($this->model);
    }

    public function all(string $orderBy = '', string $orderDirection = '', int $limit = 0): object
    {
        return $this->model
                ->when(!empty($orderBy) && !empty($orderDirection), function ($query) use ($orderBy, $orderDirection) {
                    return $query->orderBy($orderBy, $orderDirection);
                })
                ->when($limit > 0, function ($query) use ($limit) {
                    return $query->limit($limit);
                })
                ->get();
    }

    public function store(array $request): Model
    {
        return $this->model->create($request)->fresh();
    }

    public function findById(string $id): object
    {
        return $this->model->findOrFail($id);
    }

    public function findByField(
        string $field,
        string $value,
        array $values = [],
        string $orderBy = '',
        string $orderDirection = '',
        array $additionalConditions = [],
        string $returnType = 'get' // Opções: 'get', 'first', 'count'
    ): mixed 
    {
        $query = $this->model
            ->when($value !== null, fn($q) => $q->where($field, $value))
            ->when(!empty($values), fn($q) => $q->whereIn($field, $values))
            ->when(!empty($additionalConditions), function ($q) use ($additionalConditions) {
                foreach ($additionalConditions as $condition) {
                    $q->where(...$condition);
                }
            })
            ->when(!empty($orderBy) && !empty($orderDirection), fn($q) => $q->orderBy($orderBy, $orderDirection));
    
        return match ($returnType) {
            'first' => $query->first(),
            'count' => $query->count(),
            'exists' => $query->exists(),
            default  => $query->get(),
        };
    }

    public function update(string $id, array $data): Model
    {
        
        $object = $this->findById($id);
        if (!empty($data)) {
            $object->update($data);
        }
    
        return $object->refresh();
    }

    public function updateOrCreate(array $attributes, array $data): Model
    {
        return $this->model->updateOrCreate($attributes, $data)->refresh();
    }

    public function delete(string $id): bool
    {
        return $this->model->findOrFail($id)->delete();
    }
}
