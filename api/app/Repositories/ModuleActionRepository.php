<?php

namespace App\Repositories;

use App\Repositories\Contracts\ModuleActionRepositoryInterface;
use App\Models\ModuleAction;

class ModuleActionRepository implements ModuleActionRepositoryInterface
{
    protected $entity;

    public function __construct(ModuleAction $module)
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

        $module->action = $data['action'];
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

    /**
     * Verifica se ja existe o módulo
     *
     * @return array
    */
    public function exists(?int $id, string $action)
    {
        return $this->entity->where('id', '!=', $id)->where('action', $action)->first();
    }

    /**
     * Consulta por campos
     *
     * Exemplo de uso do filter
     * $filters = [
     *   ['idade', '>', 25],
     *   ['cidade', '=', 'São Paulo'],
     * ];
     *
     * @return array
    */
    public function findBy(array $filters)
    {
        $query = $this->entity->query();

        foreach ($filters as $filter) {
            list($field, $operator, $value) = $filter;

            $query->where($field, $operator, $value);
        }

        return $query->first();
    }
}
