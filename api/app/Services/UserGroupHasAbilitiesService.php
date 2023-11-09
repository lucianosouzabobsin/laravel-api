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
    public function getAll(array $filters = [])
    {
        $userGroupAbilities = $this->userGroupHasAbilitiesRepository->getAll($filters);

        // Novo array para armazenar os grupos separados por grupo de usuários
        $groupedByNames = [];

        // Percorre o resultado
        foreach ($userGroupAbilities as $group) {
            $name = $group["name"];

            // Verifica se a chave já existe no array de grupo de usuários
            if (!isset($groupedByNames[$name])) {
                $groupedByNames[$name] = [];
                $groupedByNames[$name]['abilities'] = [];
                $groupedByNames[$name]['text'] = [];
            }

            // Adiciona a habilidade ao grupo de usuários
            array_push($groupedByNames[$name]['abilities'], [
                'ability_id' => $group['ability_id'],
                'ability' => $group['ability']
            ]);

            // // Adiciona a habilidade ao grupo de usuários no formato texto
            array_push($groupedByNames[$name]['text'], $group['ability']);
        }

        return $groupedByNames;
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

         // Deleta os vinculos para recriar abaixo
        $this->userGroupHasAbilitiesRepository->delete($userGroupId);

        $abilities = [];
        foreach ($abilitiesIds as $abilityId) {
            $data = ['user_group_id' => $userGroupId, 'ability_id' => $abilityId];

            $make =  $this->userGroupHasAbilitiesRepository->make($data);

            array_push($abilities, ['ability_id' => $make['ability_id']]);
        }

        $userGroupAbilities['user_group_id'][$userGroupId] = $abilities;

        return $userGroupAbilities;
    }
}
