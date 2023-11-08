<?php

namespace App\Repositories\Contracts;

interface AbilityRepositoryInterface
{
    public function make(array $data);
    public function getAll();
    public function find(int $id);
    public function active(int $id);
    public function exists(?int $id, string $ability);

}
