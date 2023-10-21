<?php

namespace App\Repositories;

use App\Repositories\Contracts\ModuleRepositoryInterface;
use App\Models\Module;


class ModuleRepository implements ModuleRepositoryInterface
{
    protected $entity;

    public function __construct(Module $module)
    {
        $this->entity = $module;
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
     * Update module
     * @param array $data
     *
     * @return array
     */
    public function update(array $data)
    {
        $module = $this->entity->find($data['id']);

        $module->name = $data['name'];
        $module->description = $data['description'];
        $module->active = $data['active'];
        $module->save();

        return $module;
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
     * Return module
     * @param int $id
     *
     * @return array
     */
    public function active(int $id)
    {
        $module = $this->entity->find($id);
        $module->active = (int) !$module->active;
        $module->save();

        return $module;
    }
}
