<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $password = Hash::make(env('SUPER_PASSWORD'));

        User::create([
            'user_group_id' => 1,
            'name' => 'Usuário Super',
            'email' => env('SUPER_EMAIL'),
            'password' => $password
        ]);
    }
}
