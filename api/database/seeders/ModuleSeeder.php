<?php

namespace Database\Seeders;

use App\Models\Module;
use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Module::create([
            'name' => 'all',
            'nickname' => 'All',
            'description' => 'All',
            'active' => 1
        ]);
    }
}
