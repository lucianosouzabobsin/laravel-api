<?php

namespace App\Repositories\Contracts;

interface UserRepositoryInterface
{
    public function make(array $data);
    public function findUserAuth();
    public function exists(?int $id, string $email);
}
