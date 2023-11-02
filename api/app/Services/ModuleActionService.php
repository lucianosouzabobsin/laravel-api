<?php

namespace App\Services;

use App\Repositories\Contracts\ModuleActionRepositoryInterface;

class ModuleActionService
{
    protected $moduleActionRepository;

    public function __construct(ModuleActionRepositoryInterface $moduleActionRepository)
    {
        $this->moduleActionRepository = $moduleActionRepository;
    }

    /**
     * get all modules
     *
     * @return array
    */
    public function getAll()
    {
        return $this->moduleActionRepository->getAll();
    }

    /**
     * Create module
     *
     * @return array
    */
    public function make(array $data)
    {
        return $this->moduleActionRepository->make($data);
    }

    /**
     * Update module
     *
     * @return array
    */
    public function update(array $data)
    {
        return $this->moduleActionRepository->update($data);
    }

    /**
     * Update Active or Inactive
     *
     * @return array
    */
    public function active(int $id)
    {
        return $this->moduleActionRepository->active($id);
    }

    /**
     * Verifica se ja existe o módulo
     *
     * @return array
    */
    public function exists(?int $id, string $action)
    {
        return $this->moduleActionRepository->exists($id, $action);
    }
}
