<?php

namespace Tests\Feature\Controller;

use Tests\TestCase;
use Tests\Utils\TestSetup;

class UserGroupHasAbilitiesControllerTest extends TestCase
{
    protected $token;
    protected $testSetup;

    public function setUp() : void {
        parent::setUp();

        $this->testSetup = new TestSetup();
        $this->testSetup->setUp();

        $login = $this->postJson('/api/login', [
            'email' => 'usuario1@test.com',
            'password' => 'password',
        ]);

        $this->token = $login['token'];
    }

    /**
     * Testa a list de habilidades de grupos de usuários.
     *
     * @return void
     */
    public function testListUserGroupHasAbilities()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/user-group-ability-list');

        $jsonData = json_decode($response->getContent(), true);

        $size = count($jsonData);
        $this->assertEquals($size, 3);
    }

    /**
     * Testa o registro de novas habilidades para grupo de usuario.
     *
     * @return void
     */
    public function testCreateUserGroupHasAbilities()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            ])
            ->postJson('/api/user-group-ability-create', [
                'user_group_id' => '3',
                'abilities_ids' => ['1','2']
            ]);

        $jsonData = json_decode($response->getContent(), true);

        $expected = [
            3 => [
                ['ability_id' => '1'],
                ['ability_id' => '2']
            ]
        ];

        $this->assertEquals($expected, $jsonData['user_group_id']);
    }

    /**
     * Testa o registro de novas habilidades para grupo de usuario ja existente retornando erro.
     *
     * @return void
     */
    public function testErrorCreatUserGroupHasAbilitiesAlreadyExists()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            ])
            ->postJson('/api/user-group-ability-create', [
                'user_group_id' => '2',
                'abilities_ids' => ['1','2']
            ]);

        $jsonData = json_decode($response->getContent(), true);

        $expected = [
            'errors' => [
                'abilities_ids' => [
                    "The ability 2 already exists for the user group."
                ]
            ]
        ];

        $this->assertEquals($expected, $jsonData);
    }
}
