<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Model;

interface RepositoryInterface
{
    public function all(string $orderBy = '', string $orderDirection = '', int $limit = 0): object;
    public function store(array $request): Model;
    public function findById(string $id): object;
    public function findByField(string $field, string $value, array $values, string $orderBy, string $orderDirection, array $additionalConditions, string $returnType): mixed;
    public function update(string $id, array $dados): Model;
    public function updateOrCreate(array $attributes, array $dados): Model;
    public function delete(string $id): bool;
}