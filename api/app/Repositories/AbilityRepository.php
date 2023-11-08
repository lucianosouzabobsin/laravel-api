<?php

namespace App\Repositories;

use App\Models\Ability;
use App\Repositories\Contracts\AbilityRepositoryInterface;

class AbilityRepository implements AbilityRepositoryInterface
{
    protected $entity;

    public function __construct(Ability $ability)
    {
        $this->entity = $ability;
    }

    /**
     * Create ability
     * @param array $data
     *
     * @return array
     */
    public function make(array $data)
    {
        return $this->entity->create($data);
    }

    /**
     * Return all ability
     *
     * @return array
     */
    public function getAll()
    {
        return $this->entity->all();
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
     * Return ability
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
     * Verifica se ja existe o ability
     *
     * @return array
    */
    public function exists(?int $id, string $ability)
    {
        return $this->entity->where('id', '!=', $id)->where('ability', $ability)->first();
    }
}
