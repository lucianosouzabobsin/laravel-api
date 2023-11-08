<?php

namespace Tests\Feature\Controller;

use Tests\TestCase;
use Tests\Utils\TestSetup;
use Illuminate\Support\Str;

class AbilityControllerTest extends TestCase
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
     * Testa a list de módulos actions permissions.
     *
     * @return void
     */
    public function testListAbility()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/ability-list');

        $jsonData = json_decode($response->getContent(), true);

        $size = count($jsonData);
        $this->assertEquals($size, 4);
    }

    /**
     * Testa o registro de ability superadmin.
     *
     * @return void
     */
    public function testCreateAbilitySuperAdminError()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            ])
            ->postJson('/api/ability-create', [
                'module_id' => '1',
                'module_action_id' => '1',
                'active' => true,
            ]);

        $jsonData = json_decode($response->getContent(), true);

        $expected = [
            'errors' => [
                'ability' => [
                    "The ability has already been taken."
                ]
            ]
        ];

        $this->assertEquals($expected, $jsonData);
    }

    /**
     * Testa o registro de ability.
     *
     * @return void
     */
    public function testCreateAbility()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            ])
            ->postJson('/api/ability-create', [
                'module_id' => '2',
                'module_action_id' => '5',
                'active' => true,
            ]);

        $jsonData = json_decode($response->getContent(), true);

        $this->assertEquals('configuracao:select', $jsonData['ability']);
        $this->assertEquals('1', $jsonData['active']);
    }

    /**
     * @Testa ativar e inativar.
     *
     * @return void
     */
    public function testActiveInactive()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            ])
            ->postJson('/api/ability-active', [
                'id' => '2'
            ]);

        $jsonData = json_decode($response->getContent(), true);

        //Inativar
        $this->assertEquals('0', $jsonData['active']);
        $this->assertEquals('2', $jsonData['id']);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            ])
            ->postJson('/api/ability-active', [
                'id' => '2'
            ]);

        $jsonData = json_decode($response->getContent(), true);

        //Ativar
        $this->assertEquals('1', $jsonData['active']);
        $this->assertEquals('2', $jsonData['id']);
    }

    /**
     * @Testa ativar e inativar habilidades de superAdmin.
     *
     * @return void
     */
    public function testActiveInactiveSuperAdminError()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            ])
            ->postJson('/api/ability-active', [
                'id' => 1
            ]);

        $jsonData = json_decode($response->getContent(), true);

        $expected = [
            'errors' => [
                'id' => [
                    "Super admin ability cannot be revoked."
                ]
            ]
        ];

        $this->assertEquals($expected, $jsonData);
    }

    /**
     * @Testa ativar e inativar com id invalido.
     *
     * @return void
     */
    public function testInvalidIdActiveInactive()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            ])
            ->postJson('/api/ability-active', [
                'id' => 5000000
            ]);

        $jsonData = json_decode($response->getContent(), true);

        $expected = [
            'errors' => [
                'id' => [
                    "The ability does not exist."
                ]
            ]
        ];

        $this->assertEquals($expected, $jsonData);
    }

    /**
     * Testa envio de id nulo.
     *
     * @return void
     */
    public function testIdNullActiveInactive()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            ])
            ->postJson('/api/ability-active', []);

        $jsonData = json_decode($response->getContent(), true);

        $expected = [
            'errors' => [
                'id' => [
                    "The id field is required."
                ]
            ]
        ];

        $this->assertEquals($expected, $jsonData);
    }


    /**
     *  @dataProvider providerErrorAbilityRules
     *
     * Testa as validações nas regras do AbilityRules.
     *
     * @return void
     */
    public function testErrorCreateAbilityRules($moduleId, $moduleActionId, $expected)
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            ])
            ->postJson('/api/ability-create', [
                'module_id' => $moduleId,
                'module_action_id' => $moduleActionId,
                'active' => true,
            ]);

        $jsonData = json_decode($response->getContent(), true);

        $this->assertEquals($expected, $jsonData);
    }

    public static function providerErrorAbilityRules ()
    {
        return [
            [
                2,
                2,
                [
                    'errors' => [
                        'ability' => [
                            "The ability has already been taken."
                        ]
                    ]
                ]
            ],
            [
                5000,
                1,
                [
                    'errors' => [
                        'ability' => [
                            "The module do not exist."
                        ]
                    ]
                ]
            ],
            [
                1,
                5000,
                [
                    'errors' => [
                        'ability' => [
                            "The action do not exist."
                        ]
                    ]
                ]
            ],
            [
                5000,
                5000,
                [
                    'errors' => [
                        'ability' => [
                            "The module and action do not exist."
                        ]
                    ]
                ]
            ],
        ];
    }
}
