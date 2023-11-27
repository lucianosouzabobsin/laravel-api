<?php

namespace App\Services;

use App\Repositories\Contracts\UserGroupRepositoryInterface;

class UserGroupService
{
    protected $userGroupRepository;

    /**
     * SUPER_ADMIN usuário superadmin
     **/
    const SUPER_ADMIN = "superadmin";

    public function __construct(UserGroupRepositoryInterface $userGroupRepository)
    {
        $this->userGroupRepository = $userGroupRepository;
    }

    /**
     * get all modules
     *
     * @return array
    */
    public function list(array $filters, array $options)
    {
        $data = $this->userGroupRepository->list($filters, $options);
        $count = $this->userGroupRepository->count($filters);

        return ['data' => $data, 'count' => $count];
    }

    /**
     * Find By id
     *
     * @return array
    */
    public function find(int $id)
    {
        return $this->userGroupRepository->find($id);
    }

    /**
     * Create module
     *
     * @return array
    */
    public function make(array $data)
    {
        return $this->userGroupRepository->make($data);
    }

    /**
     * Update module
     *
     * @return array
    */
    public function update(array $data)
    {
        return $this->userGroupRepository->update($data);
    }

    /**
     * Update Active or Inactive
     *
     * @return array
    */
    public function active(int $id)
    {
        return $this->userGroupRepository->active($id);
    }

    /**
     * Verifica se ja existe
     *
     * @return array
    */
    public function exists(?int $id, string $name)
    {
        return $this->userGroupRepository->exists($id, $name);
    }
}
