<?php

namespace Database\Seeders;

use App\Models\UserGroup;
use Illuminate\Database\Seeder;

class UserGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserGroup::create([
            'name' => 'superadmin',
            'description' => 'Super Administrador',
            'active' => 1
        ]);
    }
}
