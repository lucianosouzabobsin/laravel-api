<?php

namespace App\Services;

use App\Repositories\Contracts\UserGroupHasAbilitiesRepositoryInterface;

class UserGroupHasAbilitiesService
{
    protected $userGroupHasAbilitiesRepository;

    public function __construct(UserGroupHasAbilitiesRepositoryInterface $userGroupHasAbilitiesRepository)
    {
        $this->userGroupHasAbilitiesRepository = $userGroupHasAbilitiesRepository;
    }

    /**
     * get all modules
     *
     * @return array
    */
    public function getAll()
    {
        return $this->userGroupHasAbilitiesRepository->getAll();
    }

    /**
     * Create module
     *
     * @return array
    */
    public function make(array $data)
    {
        $userGroupId = $data['user_group_id'];
        $abilitiesIds = $data['abilities_ids'];

        $abilities = [];
        foreach ($abilitiesIds as $abilityId) {
            $data = ['user_group_id' => $userGroupId, 'ability_id' => $abilityId];

            $make =  $this->userGroupHasAbilitiesRepository->make($data);

            array_push($abilities, ['ability_id' => $make['ability_id']]);
        }

        $userGroupAbilities['user_group_id'][$userGroupId] = $abilities;

        return $userGroupAbilities;
    }


    /**
     * Verifica se ja existe
     *
     * @return array
    */
    public function exists(?int $userGroupId, ?int $abilityId)
    {
        return $this->userGroupHasAbilitiesRepository->exists($userGroupId, $abilityId);
    }
}
