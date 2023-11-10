<?php

namespace Database\Seeders;

use App\Models\UserGroupHasAbilities;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserGroupHasAbilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserGroupHasAbilities::create([
            'user_group_id' => 1,
            'ability_id' => 1
        ]);
    }
}
