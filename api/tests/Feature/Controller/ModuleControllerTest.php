<?php

namespace Tests\Feature\Controller;

use Tests\TestCase;
use Tests\Utils\TestSetup;

class ModuleControllerTest extends TestCase
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
     * Testa a list de módulos.
     *
     * @return void
     */
    public function testListModule()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/module-list');

        $jsonData = json_decode($response->getContent(), true);

        $size = count($jsonData);
        $this->assertEquals($size, 7);
    }

    /**
     * Testa o registro de um novo módulo.
     *
     * @return void
     */
    public function testCreateModule()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            ])
            ->postJson('/api/module-create', [
                'name' => 'teste',
                'nickname' => 'Teste',
                'description' => 'Descrição do teste 2',
                'active' => true,
            ]);

        $jsonData = json_decode($response->getContent(), true);

        $this->assertEquals('teste', $jsonData['name']);
        $this->assertEquals('Teste', $jsonData['nickname']);
        $this->assertEquals('Descrição do teste 2', $jsonData['description']);
        $this->assertEquals('1', $jsonData['active']);
    }

    /**
     * Testa o registro de um novo módulo ja existente retornando erro.
     *
     * @return void
     */
    public function testErrorCreateModuleAlreadyExists()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            ])
            ->postJson('/api/module-create', [
                'name' => 'cadastro',
                'nickname' => 'Cadastro',
                'description' => 'Descrição do teste 3',
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
     * Testa o update de um módulo.
     *
     * @return void
     */
    public function testUpdateModule()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            ])
            ->postJson('/api/module-update', [
                'id' => 2,
                'name' => 'configuracaooo',
                'nickname' => 'Configuraçãoooo',
                'description' => 'Descrição do teste',
                'active' => false,
            ]);

        $jsonData = json_decode($response->getContent(), true);

        $this->assertEquals('configuracaooo', $jsonData['name']);
        $this->assertEquals('Configuraçãoooo', $jsonData['nickname']);
        $this->assertEquals('Descrição do teste', $jsonData['description']);
        $this->assertEquals('0', $jsonData['active']);
        $this->assertEquals('2', $jsonData['id']);
    }


    /**
     * Testa o update de um módulo ja existente em outra chave.
     *
     * @return void
     */
    public function testErrorUpdateModuleAnotherAlreadyExists()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            ])
            ->postJson('/api/module-update', [
                'id' => 2,
                'name' => 'usuarios',
                'nickname' => 'Usuários',
                'description' => 'Descrição do teste',
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
            ->postJson('/api/module-active', [
                'id' => '1'
            ]);

        $jsonData = json_decode($response->getContent(), true);

        //Inativar
        $this->assertEquals('0', $jsonData['active']);
        $this->assertEquals('1', $jsonData['id']);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            ])
            ->postJson('/api/module-active', [
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
            ->postJson('/api/module-active', [
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
            ->postJson('/api/module-active', []);

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
     * @dataProvider providerCreateModuleFormatIsInvalid
     *
     * Testa o registro de um novo módulo com caracteres invalidos.
     *
     * @return void
     */
    public function testCreateModuleActionFormatIsInvalid($name, $nickname)
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            ])
            ->postJson('/api/module-create', [
                'name' => $name,
                'nickname' => $nickname,
                'description' => 'Descrição do teste',
                'active' => false,
            ]);

        $jsonData = json_decode($response->getContent(), true);

        $expected = [
            'errors' => [
                'name' => [
                    "The name field format is invalid."
                ]
            ]
        ];

        $this->assertEquals($expected, $jsonData);
    }


    /**
     *  @dataProvider providerUpdateModuleFormatIsInvalid
     *
     * Testa o registro de um update módulo com caracteres invalidos.
     *
     * @return void
     */
    public function testUpdateModuleActionFormatIsInvalid($id, $name, $nickname)
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            ])
            ->postJson('/api/module-update', [
                'id' => $id,
                'name' => $name,
                'nickname' => $nickname,
                'description' => 'Descrição do teste',
                'active' => false,
            ]);

        $jsonData = json_decode($response->getContent(), true);

        $expected = [
            'errors' => [
                'name' => [
                    "The name field format is invalid."
                ]
            ]
        ];

        $this->assertEquals($expected, $jsonData);
    }

    public static function providerCreateModuleFormatIsInvalid ()
    {
        return [
            ['Teste', 'Nickname'],
            ['teste 2', 'Nickname'],
            ['teste%', 'Nickname'],
            ['teste147', 'Nickname'],
            ['teste_teste', 'Nickname'],
            ['teste teste', 'Nickname'],
            ['testão', 'Nickname'],
            ['#@$%¨&*()__', 'Nickname'],
            ['1234567889', 'Nickname'],
            ['teste_teste', 'Nickname'],
        ];
    }

    public static function providerUpdateModuleFormatIsInvalid ()
    {
        return [
            ['1', 'Teste', 'Nickname'],
            ['2', 'teste 2', 'Nickname'],
            ['3', 'teste%', 'Nickname'],
            ['4', 'teste147', 'Nickname'],
            ['1', 'teste_teste', 'Nickname'],
            ['2', 'teste teste', 'Nickname'],
            ['3', 'testão', 'Nickname'],
            ['4', '#@$%¨&*()__', 'Nickname'],
            ['1', '1234567889', 'Nickname'],
            ['2', 'teste_teste', 'Nickname'],
        ];
    }
}
