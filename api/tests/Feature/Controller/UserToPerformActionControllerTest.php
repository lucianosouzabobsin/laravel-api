<?php

namespace Tests\Feature\Controller;

use App\Models\Ability;
use App\Models\Module;
use App\Models\ModuleAction;
use App\Models\UserGroup;
use App\Models\UserGroupHasAbilities;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserToPerformActionControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     *  @dataProvider providerData
     *
     * Testa o registro de um novo usuário.
     *
     * @return void
     */
    public function testRegisterUserNotAuthorizedToPerformThisAction(
        $userGroups,
        $modules,
        $modulesActions,
        $abilities,
        $userGroupHasAbilities
    )
    {
        foreach ($userGroups as $userGroup) {
            UserGroup::create($userGroup);
        }

        foreach ($modules as $module) {
            Module::create($module);
        }

        foreach ($modulesActions as $moduleAction) {
            ModuleAction::create($moduleAction);
        }

        foreach ($abilities as $ability) {
            Ability::create($ability);
        }

        foreach ($userGroupHasAbilities as $userGroupHasAbility) {
            UserGroupHasAbilities::create($userGroupHasAbility);
        }

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

    public static function providerData ()
    {
        $userGroups = [
            ['name' => 'superadmin', 'description' => 'Super Administrador', 'active' => 1],
            ['name' => 'admin', 'description' => 'Administrador', 'active' => 1]
        ];

        $modules = [
            ['name' => 'all', 'nickname' => 'All', 'description' => 'All', 'active' => 1],
            ['name' => 'module', 'nickname' => 'module', 'description' => 'module', 'active' => 1],
            ['name' => 'moduleaction', 'nickname' => 'moduleaction', 'description' => 'moduleaction', 'active' => 1]
        ];

        $modulesActions = [
            [ 'action' => 'all', 'active' => 1],
            [ 'action' => 'list', 'active' => 1],
            [ 'action' => 'create', 'active' => 1]
        ];

        $abilities = [
            [ 'module_id' => 1, 'module_action_id' => 1, 'ability' => '*', 'active' => 1],
            [ 'module_id' => 2, 'module_action_id' => 2, 'ability' => 'module:list', 'active' => 1],
            [ 'module_id' => 2, 'module_action_id' => 3, 'ability' => 'module:create', 'active' => 1],
            [ 'module_id' => 3, 'module_action_id' => 2, 'ability' => 'moduleaction:list', 'active' => 1],
            [ 'module_id' => 3, 'module_action_id' => 3, 'ability' => 'moduleaction:create', 'active' => 1]
        ];

        $userGroupHasAbilities = [
            [ 'user_group_id' => 1, 'ability_id' => 1],
            [ 'user_group_id' => 2, 'ability_id' => 2],
            [ 'user_group_id' => 2, 'ability_id' => 3]
        ];

        return [
            [
                $userGroups,
                $modules,
                $modulesActions,
                $abilities,
                $userGroupHasAbilities
            ]
        ];
    }
}
