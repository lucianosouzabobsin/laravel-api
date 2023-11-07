<?php

namespace App\Services;

use App\Repositories\Contracts\AbilityRepositoryInterface;
use App\Repositories\Contracts\ModuleActionRepositoryInterface;
use App\Repositories\Contracts\ModuleRepositoryInterface;

class AbilityService
{
    protected $abilityRepository;
    protected $moduleRepository;
    protected $moduleActionRepository;

    /**
     * Constantes que montam o "Superadmin"
     * ALL_MODULE Acesso a todos os módulos
     * ALL_ACTION Acesso a todoas as actions
     * ALL_ABILITY habilidade que tem acesso a tudo "Superadmin"
     **/
    const ALL_MODULE = "1";
    const ALL_ACTION = "1";
    const ALL_ABILITY = "*";

    /**
     * Formato que deve ser montado o nome da Ability
     * NAME_FORMAT é o formato que deve ser montado, module:action
     * NAME_SEPARATOR é o separador em si, no caso ":"
     **/
    const NAME_FORMAT = "%s:%s";
    const NAME_SEPARATOR = ":";

    public function __construct(
        AbilityRepositoryInterface $abilityRepository,
        ModuleRepositoryInterface $moduleRepository,
        ModuleActionRepositoryInterface $moduleActionRepository
    ) {
        $this->abilityRepository = $abilityRepository;
        $this->moduleRepository = $moduleRepository;
        $this->moduleActionRepository = $moduleActionRepository;
    }

    /**
     * get all ability
     *
     * @return array
    */
    public function getAll()
    {
        return $this->abilityRepository->getAll();
    }

    /**
     * Create ability
     *
     * @return array
    */
    public function make(array $data)
    {
        return $this->abilityRepository->make($data);
    }

    /**
     * Update Active or Inactive
     *
     * @return array
    */
    public function active(int $id)
    {
        return $this->abilityRepository->active($id);
    }

    /**
     * Verifica se ja existe o ability
     *
     * @return array
    */
    public function exists(?int $id, string $ability)
    {
        return $this->abilityRepository->exists($id, $ability);
    }

    /**
     * Generate name ability
     *
     * @return array
    */
    public function getName(array $data)
    {
        $moduleName = "";
        $moduleActionName = "";

        if ($data['module_id'] == self::ALL_MODULE && $data['module_action_id'] == self::ALL_ACTION) {
            return self::ALL_ABILITY;
        }

        $filters = [
           ['id', '=', $data['module_id']],
        ];

        $module = $this->moduleRepository->findBy($filters);

        $filters = [
            ['id', '=', $data['module_action_id']],
         ];

        $moduleAction = $this->moduleActionRepository->findBy($filters);

        if (!is_null($module)) {
            $moduleName = $module->name;
        }

        if (!is_null($moduleAction)) {
            $moduleActionName = $moduleAction->action;
        }

        return sprintf(self::NAME_FORMAT, $moduleName, $moduleActionName);
    }
}
