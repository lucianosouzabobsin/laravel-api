<?php

namespace App\Repositories;

use App\Repositories\Contracts\UserGroupHasAbilitiesRepositoryInterface;
use App\Models\UserGroupHasAbilities;

class UserGroupHasAbilitiesRepository implements UserGroupHasAbilitiesRepositoryInterface
{
    protected $entity;

    public function __construct(UserGroupHasAbilities $entity)
    {
        $this->entity = $entity;
    }

    /**
     * Create module
     * @param array $data
     *
     * @return array
     */
    public function make(array $data)
    {
        return $this->entity->create($data);
    }

    /**
     * Return all module
     *
     * @return array
     */
    public function getAll()
    {
        return $this->entity->all();
    }

    /**
     * Verifica se ja existe o módulo
     *
     * @return array
    */
    public function exists(?int $userGroupId, ?int $abilityId)
    {
        return $this->entity->where('user_group_id', $userGroupId)->where('ability_id', $abilityId)->first();
    }
}
