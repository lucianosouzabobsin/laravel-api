<?php

namespace Database\Seeders;

use App\Models\Ability;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AbilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Ability::create([
            'module_id' => 1,
            'module_action_id' => 1,
            'ability' => '*',
            'active' => 1,
        ]);
    }
}
