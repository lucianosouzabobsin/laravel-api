<?php

namespace Tests\Feature\Controller;

use Tests\TestCase;
use Tests\Utils\TestSetup;

class ModuleActionControllerTest extends TestCase
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
     * Testa a list de módulos actions.
     *
     * @return void
     */
    public function testListModuleAction()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/module-action-list');

        $jsonData = json_decode($response->getContent(), true);

        $size = count($jsonData);
        $this->assertEquals($size, 4);
    }

    /**
     * Testa o registro de um novo módulo action.
     *
     * @return void
     */
    public function testCreateModuleAction()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            ])
            ->postJson('/api/module-action-create', [
                'action' => 'teste',
                'active' => true,
            ]);

        $jsonData = json_decode($response->getContent(), true);

        $this->assertEquals('teste', $jsonData['action']);
        $this->assertEquals('1', $jsonData['active']);
    }

    /**
     * Testa o registro de um novo módulo action ja existente retornando erro.
     *
     * @return void
     */
    public function testErrorCreateModuleActionAlreadyExists()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            ])
            ->postJson('/api/module-action-create', [
                'action' => 'update',
                'active' => true,
            ]);

        $jsonData = json_decode($response->getContent(), true);

        $expected = [
            'errors' => [
                'action' => [
                    "The action has already been taken."
                ]
            ]
        ];

        $this->assertEquals($expected, $jsonData);
    }


    /**
     * Testa o update de um módulo action.
     *
     * @return void
     */
    public function testUpdateModuleAction()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            ])
            ->postJson('/api/module-action-update', [
                'id' => 1,
                'action' => 'createnovo',
                'active' => false,
            ]);

        $jsonData = json_decode($response->getContent(), true);

        $this->assertEquals('createnovo', $jsonData['action']);
        $this->assertEquals('0', $jsonData['active']);
        $this->assertEquals('1', $jsonData['id']);
    }


    /**
     * Testa o update de um módulo action ja existente em outra chave.
     *
     * @return void
     */
    public function testErrorUpdateModuleActionAnotherAlreadyExists()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            ])
            ->postJson('/api/module-action-update', [
                'id' => 2,
                'action' => 'select',
                'active' => false,
            ]);

        $jsonData = json_decode($response->getContent(), true);

        $expected = [
            'errors' => [
                'action' => [
                    "The action has already been taken."
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
            ->postJson('/api/module-action-active', [
                'id' => '1'
            ]);

        $jsonData = json_decode($response->getContent(), true);

        //Inativar
        $this->assertEquals('0', $jsonData['active']);
        $this->assertEquals('1', $jsonData['id']);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            ])
            ->postJson('/api/module-action-active', [
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
            ->postJson('/api/module-action-active', [
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
            ->postJson('/api/module-action-active', []);

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
     * @dataProvider providerCreateModuleActionFormatIsInvalid
     *
     * Testa o registro de um novo módulo action com caracteres invalidos.
     *
     * @return void
     */
    public function testCreateModuleActionFormatIsInvalid($action)
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            ])
            ->postJson('/api/module-action-create', [
                'action' => $action,
                'active' => true,
            ]);

        $jsonData = json_decode($response->getContent(), true);

        $expected = [
            'errors' => [
                'action' => [
                    "The action field format is invalid."
                ]
            ]
        ];

        $this->assertEquals($expected, $jsonData);
    }


    /**
     *  @dataProvider providerUpdateModuleActionFormatIsInvalid
     *
     * Testa o registro de um update módulo action com caracteres invalidos.
     *
     * @return void
     */
    public function testUpdateModuleActionFormatIsInvalid($id, $action)
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            ])
            ->postJson('/api/module-action-update', [
                'id' => $id,
                'action' => $action,
                'active' => false,
            ]);

        $jsonData = json_decode($response->getContent(), true);

        $expected = [
            'errors' => [
                'action' => [
                    "The action field format is invalid."
                ]
            ]
        ];

        $this->assertEquals($expected, $jsonData);
    }

    public static function providerCreateModuleActionFormatIsInvalid ()
    {
        return [
            ['Teste'],
            ['teste 2'],
            ['teste%'],
            ['teste147'],
            ['teste_teste'],
            ['teste teste'],
            ['testão'],
            ['#@$%¨&*()__'],
            ['1234567889'],
            ['teste_teste'],
        ];
    }

    public static function providerUpdateModuleActionFormatIsInvalid ()
    {
        return [
            ['1', 'Teste'],
            ['2', 'teste 2'],
            ['3', 'teste%'],
            ['4', 'teste147'],
            ['1', 'teste_teste'],
            ['2', 'teste teste'],
            ['3', 'testão'],
            ['4', '#@$%¨&*()__'],
            ['1', '1234567889'],
            ['2', 'teste_teste'],
        ];
    }
}
