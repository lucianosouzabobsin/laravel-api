<?php

namespace App\Repositories\Contracts;

interface ModuleRepositoryInterface
{
    public function make(array $data);
    public function update(array $data);
    public function getAll();
    public function active(int $id);
    public function exists(?int $id, string $name);
    public function findBy(array $filters);
}
