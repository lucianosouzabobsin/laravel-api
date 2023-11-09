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
     * Testa a list all de habilidades de grupos de usuários.
     *
     * @return void
     */
    public function testListAllUserGroupHasAbilities()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/user-group-ability-list');

        $jsonData = json_decode($response->getContent(), true);

        $expected = [
            "superadmin" => [
                "abilities" =>  [
                    [
                    "ability_id" => 1,
                    "ability" => "*"
                    ]
                ],
                "text" =>  [
                    "*"
                ]
            ],
            "admin" =>  [
                "abilities" =>  [
                    [
                        "ability_id" => 2,
                        "ability" => "configuracao:create"
                    ],
                    [
                        "ability_id" => 3,
                        "ability" => "configuracao:update"
                    ]
                ],
                "text" =>  [
                    "configuracao:create", "configuracao:update"
                ]
            ]
        ];

        $size = count($jsonData);
        $this->assertEquals($size, 2);
        $this->assertEquals($expected, $jsonData);
    }

    /**
     * Testa a list by user group id de habilidades de grupos de usuários.
     *
     * @return void
     */
    public function testListByUserGroupIdUserGroupHasAbilities()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/user-group-ability-list', [
            'user_group_id' => '2',
        ]);

        $jsonData = json_decode($response->getContent(), true);

        $expected = [
            "admin" =>  [
                "abilities" =>  [
                    [
                        "ability_id" => 2,
                        "ability" => "configuracao:create"
                    ],
                    [
                        "ability_id" => 3,
                        "ability" => "configuracao:update"
                    ]
                ],
                "text" =>  [
                    "configuracao:create", "configuracao:update"
                ]
            ]
        ];

        $size = count($jsonData);
        $this->assertEquals($size, 1);
        $this->assertEquals($expected, $jsonData);
    }

    /**
     * Testa a list by user group id não existente de habilidades de grupos de usuários.
     *
     * @return void
     */
    public function testListByUserGroupIdNotExistsUserGroupHasAbilities()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/user-group-ability-list', [
            'user_group_id' => '300000',
        ]);

        $jsonData = json_decode($response->getContent(), true);

        $expected = [];

        $size = count($jsonData);
        $this->assertEquals($size, 0);
        $this->assertEquals($expected, $jsonData);
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
                'abilities_ids' => ['2','3']
            ]);

        $jsonData = json_decode($response->getContent(), true);

        $expected = [
            3 => [
                ['ability_id' => '2'],
                ['ability_id' => '3']
            ]
        ];

        $this->assertEquals($expected, $jsonData['user_group_id']);
    }

    /**
     * Testa o registro de novas habilidade superadmin para grupo de usuario retornando erro.
     *
     * @return void
     */
    public function testErrorCreateUserGroupHasAbilitiesSuperAdmin()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            ])
            ->postJson('/api/user-group-ability-create', [
                'user_group_id' => '2',
                'abilities_ids' => ['1','20000000000']
            ]);

        $jsonData = json_decode($response->getContent(), true);

        $expected = [
            'errors' => [
                'abilities_ids' => [
                    "Superadmin ability cannot be added."
                ]
            ]
        ];

        $this->assertEquals($expected, $jsonData);
    }

    /**
     * Testa o registro de novas habilidades não existentes para grupo de usuario retornando erro.
     *
     * @return void
     */
    public function testErrorCreateUserGroupHasAbilitiesAbilityDoesExists()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            ])
            ->postJson('/api/user-group-ability-create', [
                'user_group_id' => '2',
                'abilities_ids' => ['2','20000000000']
            ]);

        $jsonData = json_decode($response->getContent(), true);

        $expected = [
            'errors' => [
                'abilities_ids' => [
                    "The ability 20000000000 does not exist."
                ]
            ]
        ];

        $this->assertEquals($expected, $jsonData);
    }
}
