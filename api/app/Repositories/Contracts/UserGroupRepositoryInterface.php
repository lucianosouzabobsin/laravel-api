<?php

namespace App\Repositories\Contracts;

interface UserGroupRepositoryInterface
{
    public function make(array $data);
    public function update(array $data);
    public function list(array $filters, array $options);
    public function count(array $filters);
    public function find(int $id);
    public function active(int $id);
    public function exists(?int $id, string $name);

}
