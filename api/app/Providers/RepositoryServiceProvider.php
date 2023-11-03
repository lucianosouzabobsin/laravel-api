<?php

namespace App\Providers;

use App\Repositories\Contracts\{
    ModuleActionPermissionRepositoryInterface,
    ModuleActionRepositoryInterface,
    UserRepositoryInterface,
    ModuleRepositoryInterface,
    UserGroupRepositoryInterface
};
use App\Repositories\{
    ModuleActionPermissionRepository,
    ModuleActionRepository,
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

        $this->app->bind(
            ModuleActionRepositoryInterface::class,
            ModuleActionRepository::class
        );

        $this->app->bind(
            ModuleActionPermissionRepositoryInterface::class,
            ModuleActionPermissionRepository::class
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
