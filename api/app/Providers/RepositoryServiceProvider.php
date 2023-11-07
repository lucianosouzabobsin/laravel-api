<?php

namespace App\Providers;

use App\Repositories\Contracts\{
    AbilityRepositoryInterface,
    ModuleActionRepositoryInterface,
    UserRepositoryInterface,
    ModuleRepositoryInterface,
    UserGroupRepositoryInterface
};
use App\Repositories\{
    AbilityRepository,
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
            AbilityRepositoryInterface::class,
            AbilityRepository::class
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
