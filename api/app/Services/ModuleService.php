<?php

namespace App\Services;

use App\Repositories\Contracts\ModuleRepositoryInterface;

class ModuleService
{
    protected $moduleRepository;

    public function __construct(ModuleRepositoryInterface $moduleRepository)
    {
        $this->moduleRepository = $moduleRepository;
    }

    /**
     * get all modules
     *
     * @return array
    */
    public function getAll()
    {
        return $this->moduleRepository->getAll();
    }

    /**
     * Create module
     *
     * @return array
    */
    public function make(array $data)
    {
        return $this->moduleRepository->make($data);
    }

    /**
     * Update module
     *
     * @return array
    */
    public function update(array $data)
    {
        return $this->moduleRepository->update($data);
    }

    /**
     * Update Active or Inactive
     *
     * @return array
    */
    public function active(int $id)
    {
        return $this->moduleRepository->active($id);
    }
}