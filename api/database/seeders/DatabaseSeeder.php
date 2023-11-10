<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UserGroupSeeder::class);
        $this->call(ModuleSeeder::class);
        $this->call(ModuleActionSeeder::class);
        $this->call(AbilitySeeder::class);
        $this->call(UserGroupHasAbilitySeeder::class);
        $this->call(UserSuperAdminSeeder::class);
    }
}
