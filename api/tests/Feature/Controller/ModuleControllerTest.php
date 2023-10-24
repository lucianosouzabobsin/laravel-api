<?php

namespace Tests\Feature\Controller;

use App\Models\Module;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModuleControllerTest extends TestCase
{
    protected $module;
    protected $token;

   use RefreshDatabase;

    public function setUp() : void
    {
        parent::setUp();

        // Cria um novo módulo diretamente no banco de dados de teste
        $this->module = Module::create([
            'name' => 'Teste',
            'description' => 'Descrição do teste',
            'active' => true,
        ]);

        $register = $this->postJson('/api/register', [
            'name' => 'Jane Doe',
            'email' => 'jane.doe@example.com',
            'password' => 'password456',
        ]);

        $this->token = $register['token'];
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
                'name' => 'Teste2',
                'description' => 'Descrição do teste 2',
                'active' => true,
            ]);

        $jsonData = json_decode($response->getContent(), true);
        $this->assertEquals('Teste2', $jsonData['name']);
        $this->assertEquals('Descrição do teste 2', $jsonData['description']);
        $this->assertEquals('1', $jsonData['active']);
    }

    /**
     *
     * @return void
     */
    public function testErrorCreateModuleAlreadyExists()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            ])
            ->postJson('/api/module-create', [
                'name' => 'Teste',
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

        // Obter o ID do módulo criado
        $module = Module::where('name', 'Teste')->first();
        $id = $module->id;

        $expected = [
            [
                'id' => $id,
                'name' => 'Teste',
                'description' => 'Descrição do teste',
                'active' => true,
            ],
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
        // Obter o ID do módulo criado
        $module = Module::where('name', 'Teste')->first();
        $id = $module->id;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            ])
            ->postJson('/api/module-update', [
                'id' => $id,
                'name' => 'Teste Alterado',
                'description' => 'Descrição do teste',
                'active' => false,
            ]);

        $jsonData = json_decode($response->getContent(), true);

        $this->assertEquals('Teste Alterado', $jsonData['name']);
        $this->assertEquals('Descrição do teste', $jsonData['description']);
        $this->assertEquals('0', $jsonData['active']);
        $this->assertEquals($id, $jsonData['id']);
    }

    /**
     * Testa o update de um módulo ja existente em outra chave.
     *
     * @return void
     */
    public function testErrorUpdateModuleAnotherAlreadyExists()
    {
        // Cria um teste 2
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            ])
            ->postJson('/api/module-create', [
                'name' => 'Teste2',
                'description' => 'Descrição do teste 2',
                'active' => true,
            ]);


        // Obter o ID do módulo criado
        $module = Module::where('name', 'Teste')->first();
        $id = $module->id;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            ])
            ->postJson('/api/module-update', [
                'id' => $id,
                'name' => 'Teste2',
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
        // Obter o ID do módulo criado
        $module = Module::where('name', 'Teste')->first();
        $id = $module->id;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            ])
            ->postJson('/api/module-active', [
                'id' => $id
            ]);

        $jsonData = json_decode($response->getContent(), true);

        //Inativar
        $this->assertEquals('0', $jsonData['active']);
        $this->assertEquals($id, $jsonData['id']);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            ])
            ->postJson('/api/module-active', [
                'id' => $id
            ]);

        $jsonData = json_decode($response->getContent(), true);

        //Ativar
        $this->assertEquals('1', $jsonData['active']);
        $this->assertEquals($id, $jsonData['id']);
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
    public function testNullIdActiveInactive()
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
}
