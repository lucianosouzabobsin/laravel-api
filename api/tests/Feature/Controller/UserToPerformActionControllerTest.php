<?php

namespace Tests\Feature\Controller;

use App\Models\Ability;
use App\Models\Module;
use App\Models\ModuleAction;
use App\Models\User;
use App\Models\UserGroup;
use App\Models\UserGroupHasAbilities;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserToPerformActionControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Testa o registro de um novo usuário.
     *
     * @return void
     */
    public function testRegisterUserNotAuthorizedToPerformThisAction()
    {
        UserGroup::create([
            'name' => 'superadmin',
            'description' => 'Super Administrador',
            'active' => 1
        ]);

        UserGroup::create([
            'name' => 'admin',
            'description' => 'Administrador',
            'active' => 1
        ]);

        Module::create([
            'name' => 'all',
            'nickname' => 'All',
            'description' => 'All',
            'active' => 1
        ]);
        Module::create([
            'name' => 'module',
            'nickname' => 'module',
            'description' => 'module',
            'active' => 1
        ]);
        Module::create([
            'name' => 'moduleaction',
            'nickname' => 'moduleaction',
            'description' => 'moduleaction',
            'active' => 1
        ]);

        ModuleAction::create([
            'action' => 'all',
            'active' => 1
        ]);
        ModuleAction::create([
            'action' => 'list',
            'active' => 1
        ]);
        ModuleAction::create([
            'action' => 'create',
            'active' => 1
        ]);


        Ability::create([
            'module_id' => 1,
            'module_action_id' => 1,
            'ability' => '*',
            'active' => 1,
        ]);
        Ability::create([
            'module_id' => 2,
            'module_action_id' => 2,
            'ability' => 'module:list',
            'active' => 1,
        ]);
        Ability::create([
            'module_id' => 2,
            'module_action_id' => 3,
            'ability' => 'module:create',
            'active' => 1,
        ]);
        Ability::create([
            'module_id' => 3,
            'module_action_id' => 2,
            'ability' => 'moduleaction:list',
            'active' => 1,
        ]);
        Ability::create([
            'module_id' => 3,
            'module_action_id' => 3,
            'ability' => 'moduleaction:create',
            'active' => 1,
        ]);

        UserGroupHasAbilities::create([
            'user_group_id' => 1,
            'ability_id' => 1
        ]);
        UserGroupHasAbilities::create([
            'user_group_id' => 2,
            'ability_id' => 2
        ]);
        UserGroupHasAbilities::create([
            'user_group_id' => 2,
            'ability_id' => 3
        ]);

        $response = $this->postJson('/api/register', [
            'user_group_id' => 2,
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'password' => 'password123',
        ]);

        $token = $response['token'];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/module-action-list');

        $jsonData = json_decode($response->getContent(), true);

        $expected = [
            "message" => "You are not authorized to perform this action."
        ];

        $response->assertStatus(403);
        $this->assertEquals($expected, $jsonData);
    }
}
