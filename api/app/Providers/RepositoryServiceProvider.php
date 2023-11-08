<?php

namespace App\Providers;

use App\Repositories\Contracts\{
    AbilityRepositoryInterface,
    ModuleActionRepositoryInterface,
    UserRepositoryInterface,
    ModuleRepositoryInterface,
    UserGroupHasAbilitiesRepositoryInterface,
    UserGroupRepositoryInterface
};
use App\Repositories\{
    AbilityRepository,
    ModuleActionRepository,
    UserRepository,
    ModuleRepository,
    UserGroupHasAbilitiesRepository,
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

        $this->app->bind(
            UserGroupHasAbilitiesRepositoryInterface::class,
            UserGroupHasAbilitiesRepository::class
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
