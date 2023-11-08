<?php

namespace App\Repositories\Contracts;

interface UserGroupHasAbilitiesRepositoryInterface
{
    public function make(array $data);
    public function getAll();
    public function exists(?int $userGroupId, ?int $abilityId);
}
