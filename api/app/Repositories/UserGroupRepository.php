<?php

namespace App\Repositories;

use App\Repositories\Contracts\UserGroupRepositoryInterface;
use App\Models\UserGroup;
use App\Services\DynamicQueryService;

class UserGroupRepository implements UserGroupRepositoryInterface
{
    protected $entity;
    protected $dynamicQueryService;

    public function __construct(UserGroup $entity, DynamicQueryService $dynamicQueryService)
    {
        $this->entity = $entity;
        $this->dynamicQueryService = $dynamicQueryService;
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
     *
     * Não deve ser feito update no name
     *
     * @param array $data
     *
     * @return array
     */
    public function update(array $data)
    {
        $entity = $this->entity->find($data['id']);

        $entity->description = $data['description'];
        $entity->active = $data['active'];
        $entity->save();

        return $entity;
    }

    /**
     * List
     *
     * @return array
    */
    public function list(array $filters, array $options)
    {
        $query = $this->entity->query();
        return $this->dynamicQueryService->buildQuery($query, $filters, $options)->get();
    }

    /**
     * List Cunt
     *
     * @return array
    */
    public function count(array $filters)
    {
        $query = $this->entity->query();
        return $this->dynamicQueryService->buildQuery($query, $filters)->count();
    }

    /**
     * Return ability
     * @param int $id
     *
     * @return array
     */
    public function find(int $id)
    {
        return $this->entity->where('id', $id)->first();
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
