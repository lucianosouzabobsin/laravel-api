<?php

namespace Tests\Feature\Controller;

use Tests\TestCase;
use Tests\Utils\TestSetup;
use Illuminate\Support\Str;

class UserGroupControllerTest extends TestCase
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
     * Testa a list de grupos de usuários.
     *
     * @return void
     */
    public function testListUserGroup()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/user-group-list');

        $jsonData = json_decode($response->getContent(), true);

        $size = $jsonData['count'];
        $this->assertEquals($size, 3);
    }

    /**
     * Testa a list de grupos de usuários.
     *
     * @return void
     */
    public function testListwithFiltersEqualsAndOptionsUserGroup()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/user-group-list',  [
            'filters' => [
                [
                    'field' => 'name',
                    'operator' => '=',
                    'value' => 'superadmin'
                ]
            ],
            'options' => [
                'sortBy' => ["name"],
                'sortDirection' => ["asc"],
                'perPage' => 2,
                'page' => 1
            ],
        ]);

        $jsonData = json_decode($response->getContent(), true);

        $size = $jsonData['count'];
        $this->assertEquals($size, 1);
    }

    /**
     * Testa a list de grupos de usuários.
     *
     * @return void
     */
    public function testListwithFiltersLikeAndOptionsUserGroup()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/user-group-list',  [
            'filters' => [
                [
                    'field' => 'name',
                    'operator' => 'like',
                    'value' => 'admin'
                ]
            ],
        ]);

        $jsonData = json_decode($response->getContent(), true);

        $size = $jsonData['count'];
        $this->assertEquals($size, 2);
    }


    /**
     * Testa o registro de um novo grupo de usuario.
     *
     * @return void
     */
    public function testCreateUserGroup()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            ])
            ->postJson('/api/user-group-create', [
                'name' => 'usergroup4',
                'description' => 'Descrição do grupo 4',
                'active' => true,
            ]);

        $jsonData = json_decode($response->getContent(), true);

        $this->assertEquals('usergroup4', $jsonData['name']);
        $this->assertEquals('Descrição do grupo 4', $jsonData['description']);
        $this->assertEquals('1', $jsonData['active']);
    }

    /**
     * Testa o registro de um novo grupo de usuario ja existente retornando erro.
     *
     * @return void
     */
    public function testErrorCreateUserGroupAlreadyExists()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            ])
            ->postJson('/api/user-group-create', [
                'name' => 'superadmin',
                'description' => 'Descrição do superadmin',
                'active' => true,
            ]);

        $jsonData = json_decode($response->getContent(), true);

        $expected = [
            'errors' => [
                'name' => [
                    "The name has already been taken."
                ]
            ]
        ];

        $this->assertEquals($expected, $jsonData);
    }


    /**
     * Testa o update de um grupo de usuario.
     *
     * @return void
     */
    public function testUpdateUserGroup()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            ])
            ->postJson('/api/user-group-update', [
                'id' => 1,
                'description' => 'Descrição do superadmin',
                'active' => false,
            ]);

        $jsonData = json_decode($response->getContent(), true);

        $this->assertEquals('Descrição do superadmin', $jsonData['description']);
        $this->assertEquals('0', $jsonData['active']);
        $this->assertEquals('1', $jsonData['id']);
    }


    /**
     * Testa o update de um grupo de usuario com description estourada.
     *
     * @return void
     */
    public function testUpdateUserGroupErrorLength()
    {
        $stringLong = Str::random(300);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            ])
            ->postJson('/api/user-group-update', [
                'id' => 3,
                'description' => $stringLong,
                'active' => false,
            ]);

        $jsonData = json_decode($response->getContent(), true);

        $expected = [
            'errors' => [
                'description' => [
                    "The description field must not be greater than 255 characters."
                ]
            ]
        ];

        $this->assertEquals($expected, $jsonData);
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
            ->postJson('/api/user-group-active', [
                'id' => '2'
            ]);

        $jsonData = json_decode($response->getContent(), true);

        //Inativar
        $this->assertEquals('0', $jsonData['active']);
        $this->assertEquals('2', $jsonData['id']);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            ])
            ->postJson('/api/user-group-active', [
                'id' => '2'
            ]);

        $jsonData = json_decode($response->getContent(), true);

        //Ativar
        $this->assertEquals('1', $jsonData['active']);
        $this->assertEquals('2', $jsonData['id']);
    }

    /**
     * @Testa ativar e inativar superAdmin.
     *
     * @return void
     */
    public function testActiveInactiveSuperAdminError()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            ])
            ->postJson('/api/user-group-active', [
                'id' => 1
            ]);

        $jsonData = json_decode($response->getContent(), true);

        $expected = [
            'errors' => [
                'id' => [
                    "Super admin userGroup cannot be revoked."
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
            ->postJson('/api/user-group-active', [
                'id' => 5000000
            ]);

        $jsonData = json_decode($response->getContent(), true);

        $expected = [
            'errors' => [
                'id' => [
                    "The userGroup does not exist."
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
            ->postJson('/api/user-group-active', []);

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
}
