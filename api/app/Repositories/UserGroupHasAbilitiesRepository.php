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
     * Return all
     * @param array $filters
     *
     * @return array
     */
    public function getAll(array $filters)
    {
        return $this->entity->select(
            'user_group_has_abilities.user_group_id',
            'user_group_has_abilities.ability_id',
            'users_groups.name',
            'abilities.ability'
        )
            ->join('users_groups', 'users_groups.id', '=', 'user_group_has_abilities.user_group_id')
            ->join('abilities', 'abilities.id', '=', 'user_group_has_abilities.ability_id')
            ->where(function($query) use ($filters) {
                if (isset($filters['user_group_id'])) {
                    $query->where('user_group_id', $filters['user_group_id']);
                }
            })
            ->where('abilities.active', 1)
            ->where('users_groups.active', 1)
            ->get()
            ->toArray();
    }

    /**
     * Create module
     * @param array $data
     *
     * @return array
     */
    public function delete(int $userGroupId)
    {
        return $this->entity->where('user_group_id', $userGroupId)->delete();
    }
}
