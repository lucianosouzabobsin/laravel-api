<?php

namespace App\Providers;

use App\Repositories\Contracts\{
    UserRepositoryInterface,
    ModuleRepositoryInterface,
    UserGroupRepositoryInterface
};
use App\Repositories\{
    UserRepository,
    ModuleRepository,
    UserGroupRepository
};

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            UserRepositoryInterface::class,
            UserRepository::class
        );

        $this->app->bind(
            ModuleRepositoryInterface::class,
            ModuleRepository::class
        );

        $this->app->bind(
            UserGroupRepositoryInterface::class,
            UserGroupRepository::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
