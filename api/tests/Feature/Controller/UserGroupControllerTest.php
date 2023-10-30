<?php

namespace Tests\Feature\Controller;

use Tests\TestCase;
use Tests\Utils\TestSetup;

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
        ])->getJson('/api/user-group-list');

        $jsonData = json_decode($response->getContent(), true);

        $size = count($jsonData);
        $this->assertEquals($size, 3);
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
                'name' => 'superadmin',
                'description' => 'Descrição do superadmin',
                'active' => false,
            ]);

        $jsonData = json_decode($response->getContent(), true);

        $this->assertEquals('superadmin', $jsonData['name']);
        $this->assertEquals('Descrição do superadmin', $jsonData['description']);
        $this->assertEquals('0', $jsonData['active']);
        $this->assertEquals('1', $jsonData['id']);
    }


    /**
     * Testa o update de um grupo de usuario ja existente em outra chave.
     *
     * @return void
     */
    public function testErrorUpdateUserGroupAnotherAlreadyExists()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            ])
            ->postJson('/api/user-group-update', [
                'id' => 3,
                'name' => 'superadmin',
                'description' => 'Admin',
                'active' => false,
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
                'id' => '1'
            ]);

        $jsonData = json_decode($response->getContent(), true);

        //Inativar
        $this->assertEquals('0', $jsonData['active']);
        $this->assertEquals('1', $jsonData['id']);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            ])
            ->postJson('/api/user-group-active', [
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
            ->postJson('/api/user-group-active', [
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
