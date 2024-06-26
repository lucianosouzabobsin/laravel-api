<?php

namespace App\Repositories;

use App\Repositories\Contracts\UserRepositoryInterface;
use App\Models\User;


class UserRepository implements UserRepositoryInterface
{
    protected $entity;

    public function __construct(User $user)
    {
        $this->entity = $user;
    }

    /**
     * Create user
     *
     * @return array
     */
    public function make(array $data)
    {
        return $this->entity->create($data);
    }

    /**
     * Create user
     *
     * @return User
     */
    public function findUserAuth()
    {
        return $this->entity::find(auth()->user()->id);
    }

    /**
     * Verifica se ja existe o email
     *
     * @return array
    */
    public function exists(?int $id, string $email)
    {
        return $this->entity->where('id', '!=', $id)->where('email', $email)->first();
    }
}
