<?php

namespace Tests\Feature\Controller;

use Tests\TestCase;
use Tests\Utils\TestSetup;
use Illuminate\Support\Str;

class ModuleActionPermissionControllerTest extends TestCase
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
    public function testListModuleActionPermission()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/module-action-permission-list');

        $jsonData = json_decode($response->getContent(), true);

        $size = count($jsonData);
        $this->assertEquals($size, 4);
    }

    /**
     * Testa o registro de um novo módulo action permissions.
     *
     * @return void
     */
    public function testCreateModuleActionPermission()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            ])
            ->postJson('/api/module-action-permission-create', [
                'module_id' => '2',
                'module_action_id' => '5',
                'description' => 'Teste',
                'link' => 'api/teste',
                'active' => true,
            ]);

        $jsonData = json_decode($response->getContent(), true);

        $this->assertEquals('configuracao:select', $jsonData['name']);
        $this->assertEquals('1', $jsonData['active']);
    }

    /**
     * Testa o update de um módulo action permissions.
     *
     * @return void
     */
    public function testUpdateModuleActionPermission()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            ])
            ->postJson('/api/module-action-permission-update', [
                'id' => 2,
                'description' => 'Teste alterado',
                'link' => 'api/teste',
                'active' => false,
            ]);

        $jsonData = json_decode($response->getContent(), true);

        $this->assertEquals('configuracao:create', $jsonData['name']);
        $this->assertEquals('Teste alterado', $jsonData['description']);
        $this->assertEquals('0', $jsonData['active']);
        $this->assertEquals('2', $jsonData['id']);
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
            ->postJson('/api/module-action-permission-active', [
                'id' => '1'
            ]);

        $jsonData = json_decode($response->getContent(), true);

        //Inativar
        $this->assertEquals('0', $jsonData['active']);
        $this->assertEquals('1', $jsonData['id']);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            ])
            ->postJson('/api/module-action-permission-active', [
                'id' => '1'
            ]);

        $jsonData = json_decode($response->getContent(), true);

        //Ativar
        $this->assertEquals('1', $jsonData['active']);
        $this->assertEquals('1', $jsonData['id']);
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
            ->postJson('/api/module-action-permission-active', [
                'id' => 5000000
            ]);

        $jsonData = json_decode($response->getContent(), true);

        $expected = [
            'error' => 'Bad request',
            'description_error' => 'Attempt to read property "active" on null',
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
            ->postJson('/api/module-action-permission-active', []);

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
     * Testa o update de um módulo action permissions.
     *
     * @return void
     */
    public function testUpdateModuleActionPermissionErrorLength()
    {
        $stringLong = Str::random(300);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            ])
            ->postJson('/api/module-action-permission-update', [
                'id' => 2,
                'description' => $stringLong,
                'link' => $stringLong,
                'active' => false,
            ]);

        $jsonData = json_decode($response->getContent(), true);

        $expected = [
            'errors' => [
                'description' => [
                    "The description field must not be greater than 255 characters."
                ],
                'link' => [
                    "The link field must not be greater than 255 characters."
                ],
            ]
        ];

        $this->assertEquals($expected, $jsonData);
    }

    /**
     *  @dataProvider providerErrorCreateModuleActionPermissionRules
     *
     * Testa as validações nas regras do ModuleActionPermissionRules.
     *
     * @return void
     */
    public function testErrorCreateModuleActionPermissionRules($moduleId, $moduleActionId, $expected)
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            ])
            ->postJson('/api/module-action-permission-create', [
                'module_id' => $moduleId,
                'module_action_id' => $moduleActionId,
                'description' => 'Teste',
                'link' => 'api/teste',
                'active' => true,
            ]);

        $jsonData = json_decode($response->getContent(), true);

        $this->assertEquals($expected, $jsonData);
    }

    public static function providerErrorCreateModuleActionPermissionRules ()
    {
        return [
            [
                2,
                2,
                [
                    'errors' => [
                        'name' => [
                            "The name has already been taken."
                        ]
                    ]
                ]
            ],
            [
                5000,
                1,
                [
                    'errors' => [
                        'name' => [
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
                        'name' => [
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
                        'name' => [
                            "The module and action do not exist."
                        ]
                    ]
                ]
            ],
        ];
    }
}
