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
        $module->nickname = $data['nickname'];
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

    /**
     * Verifica se ja existe o módulo
     *
     * @return array
    */
    public function exists(?int $id, string $name)
    {
        return $this->entity->where('id', '!=', $id)->where('name', $name)->first();
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
