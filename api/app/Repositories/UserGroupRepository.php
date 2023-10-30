<?php

namespace App\Repositories;

use App\Repositories\Contracts\UserGroupRepositoryInterface;
use App\Models\UserGroup;

class UserGroupRepository implements UserGroupRepositoryInterface
{
    protected $entity;

    public function __construct(UserGroup $entity)
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
     * Update entity
     * @param array $data
     *
     * @return array
     */
    public function update(array $data)
    {
        $entity = $this->entity->find($data['id']);

        $entity->name = $data['name'];
        $entity->description = $data['description'];
        $entity->active = $data['active'];
        $entity->save();

        return $entity;
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
        $entity = $this->entity->find($id);
        $entity->active = (int) !$entity->active;
        $entity->save();

        return $entity;
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
}
