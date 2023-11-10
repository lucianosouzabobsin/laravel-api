<?php

namespace Database\Seeders;

use App\Models\ModuleAction;
use Illuminate\Database\Seeder;

class ModuleActionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ModuleAction::create([
            'action' => 'all',
            'active' => 1
        ]);
    }
}
