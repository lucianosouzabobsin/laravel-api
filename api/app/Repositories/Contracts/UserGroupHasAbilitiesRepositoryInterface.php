<?php

namespace App\Repositories\Contracts;

interface UserGroupHasAbilitiesRepositoryInterface
{
    public function make(array $data);
    public function getAll(array $filters);
    public function delete(int $userGroupId);
}
