<?php

namespace App\Services;

use App\Repositories\Contracts\ModuleActionPermissionRepositoryInterface;
use App\Repositories\Contracts\ModuleActionRepositoryInterface;
use App\Repositories\Contracts\ModuleRepositoryInterface;

class ModuleActionPermissionService
{
    protected $moduleActionPermissionRepository;
    protected $moduleRepository;
    protected $moduleActionRepository;

    /**
     * Formato que deve ser montado o nome do ModuleActionPermission
     * NAME_FORMAT é o formato que deve ser montado, module:action
     * NAME_SEPARATOR é o separador em si, no caso ":"
     **/
    const NAME_FORMAT = "%s:%s";
    const NAME_SEPARATOR = ":";

    public function __construct(
        ModuleActionPermissionRepositoryInterface $moduleActionPermissionRepository,
        ModuleRepositoryInterface $moduleRepository,
        ModuleActionRepositoryInterface $moduleActionRepository
    ) {
        $this->moduleActionPermissionRepository = $moduleActionPermissionRepository;
        $this->moduleRepository = $moduleRepository;
        $this->moduleActionRepository = $moduleActionRepository;
    }

    /**
     * get all modules
     *
     * @return array
    */
    public function getAll()
    {
        return $this->moduleActionPermissionRepository->getAll();
    }

    /**
     * Create module
     *
     * @return array
    */
    public function make(array $data)
    {
        return $this->moduleActionPermissionRepository->make($data);
    }

    /**
     * Update module
     *
     * @return array
    */
    public function update(array $data)
    {
        return $this->moduleActionPermissionRepository->update($data);
    }

    /**
     * Update Active or Inactive
     *
     * @return array
    */
    public function active(int $id)
    {
        return $this->moduleActionPermissionRepository->active($id);
    }

    /**
     * Verifica se ja existe o módulo
     *
     * @return array
    */
    public function exists(?int $id, string $name)
    {
        return $this->moduleActionPermissionRepository->exists($id, $name);
    }

    /**
     * Create module
     *
     * @return array
    */
    public function getName(array $data)
    {
        $moduleName = "";
        $moduleActionName = "";

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
