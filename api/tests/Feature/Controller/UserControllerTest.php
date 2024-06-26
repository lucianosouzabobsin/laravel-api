<?php

namespace Tests\Feature\Controller;

use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use Tests\Utils\TestSetup;

class UserControllerTest extends TestCase
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
     * Testa o registro de um novo usuário com grupo se habilidades.
     *
     * @return void
     */
    public function testRegisterUserWithUserGroupWithoutAbilities()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Usuario 4',
            'user_group_id' => 3,
            'email' => 'usuario4@test.com',
            'password' => 'password123',
        ]);

        $jsonData = json_decode($response->getContent(), true);

        $expected = [
            'errors' => [
                'user_group_id' => [
                    "The userGroup does not have abilities."
                ]
            ]
        ];

        $response->assertStatus(422);
        $this->assertEquals($expected, $jsonData);
    }

    /**
     * Testa o registro de um novo usuário.
     *
     * @return void
     */
    public function testRegisterUser()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Usuario 4',
            'user_group_id' => 2,
            'email' => 'usuario4@test.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(201);
    }

    /**
     * Testa endpoint sem nome.
     *
     * @return void
     */
    public function testRegisterUserErrorValidatorNoName()
    {
        $response = $this->postJson('/api/register', [
            'user_group_id' => 2,
            'email' => 'usuario5@test.com',
            'password' => 'password123',
        ]);

        $jsonData = json_decode($response->getContent(), true);

        $expected = [
            'errors' => [
                'name' => [
                    "The name field is required."
                ]
            ]
        ];

        $response->assertStatus(422);
        $this->assertEquals($expected, $jsonData);
    }

    /**
     * Testa endpoint sem user group id.
     *
     * @return void
     */
    public function testRegisterUserErrorValidatorNoUserGroupId()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Usuario 4',
            'email' => 'usuario5@test.com',
            'password' => 'password123',
        ]);

        $jsonData = json_decode($response->getContent(), true);

        $expected = [
            'errors' => [
                'user_group_id' => [
                    "The user group id field is required."
                ]
            ]
        ];

        $response->assertStatus(422);
        $this->assertEquals($expected, $jsonData);
    }

    /**
     * Testa endpoint com user group id invalido.
     *
     * @return void
     */
    public function testRegisterUserErrorValidatorNoUserGroupIdInvalid()
    {
        $response = $this->postJson('/api/register', [
            'user_group_id' => 2000000,
            'name' => 'Usuario 4',
            'email' => 'usuario5@test.com',
            'password' => 'password123',
        ]);

        $jsonData = json_decode($response->getContent(), true);

        $expected = [
            'errors' => [
                'user_group_id' => [
                    "The userGroup does not exist."
                ]
            ]
        ];

        $response->assertStatus(422);
        $this->assertEquals($expected, $jsonData);
    }

    /**
     * Testa endpoint sem senha.
     *
     * @return void
     */
    public function testRegisterUserErrorValidatorNoPassword()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Usuario 5',
            'user_group_id' => 2,
            'email' => 'usuario5@test.com',
            'password' => '',
        ]);

        $jsonData = json_decode($response->getContent(), true);

        $expected = [
            'errors' => [
                'password' => [
                    "The password field is required."
                ]
            ]
        ];

        $response->assertStatus(422);
        $this->assertEquals($expected, $jsonData);
    }

    /**
     * Testa endpoint sem o minimo de 8 caracteres para senha.
     *
     * @return void
     */
    public function testRegisterUserErrorValidatorBelowStringMinimumPassword()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Usuario 5',
            'user_group_id' => 2,
            'email' => 'usuario5@test.com',
            'password' => '1234567',
        ]);

        $jsonData = json_decode($response->getContent(), true);

        $expected = [
            'errors' => [
                'password' => [
                    "The password field must be at least 8 characters."
                ]
            ]
        ];

        $response->assertStatus(422);
        $this->assertEquals($expected, $jsonData);
    }

    /**
     * Testa endpoint com tentiva de fazer registro com email já existente
     *
     * @return void
     */
    public function testRegisterUserErrorValidatorUniqueEmail()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'usuario 3 repet',
            'user_group_id' => 2,
            'email' => 'usuario3@test.com',
            'password' => 'password1234666',
        ]);

        $jsonData = json_decode($response->getContent(), true);

        $expected = [
            'errors' => [
                'email' => [
                    "The e-mail has already been taken."
                ]
            ]
        ];

        $response->assertStatus(422);
        $this->assertEquals($expected, $jsonData);
    }

    /**
     * Testa o registro para um erro 404, campos informados errados.
     *
     * @return void
     */
    public function testRegisterUserFieldsWrong()
    {
        $response = $this->postJson('/api/register', [
            'campoerrado' => 'Test User',
            'campoerrado2' => 'test@example.com',
            'campoerrado3' => 'password123',
        ]);

        $jsonData = json_decode($response->getContent(), true);

        $expected = [
            'errors' => [
                'user_group_id' => [
                    "The user group id field is required."
                ],
                'name' => [
                    "The name field is required."
                ],
                'email' => [
                    "The email field is required."
                ],
                'password' => [
                    "The password field is required."
                ]
            ]
        ];

        $response->assertStatus(422);
        $this->assertEquals($expected, $jsonData);
    }


    /**
     * Testa o login de um usuário existente.
     *
     * @return void
     */
    public function testLoginUser()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'usuario2@test.com',
            'password' => 'password',
        ]);

        $response->assertStatus(201);
    }

    /**
     * Testa o login de um usuário não autorizado.
     *
     * @return void
     */
    public function testLoginUserUnauthorised()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'usuario2@test.com',
            'password' => 'passwordwrong',
        ]);

        $response->assertStatus(401);
    }

    /**
     * Testa o acesso às informações do usuário autenticado.
     *
     * @return void
     */
    public function testUserDetails()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/user');

        $jsonData = json_decode($response->getContent(), true);

        $expected = [
            'user' => [
                'id' => 1,
                'name' => 'Usuario 1',
                'email' => 'usuario1@test.com',
                'created_at' => null,
                'updated_at' => null,
                'user_group_id' => 1
            ]
        ];

        $response->assertStatus(200);
        $this->assertEquals($expected, $jsonData);
    }


    /**
     * Testa a tentativa de acesso de um usuário para uma ação que ele não tem acesso.
     *
     * @return void
     */
    public function testUserNotAuthorizedToPerformAction()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['module:list']);

        $response = $this->getJson('/api/module-action-list');

        $jsonData = json_decode($response->getContent(), true);

        $expected = [
            "message" => "You are not authorized to perform this action."
        ];

        $response->assertStatus(403);
        $this->assertEquals($expected, $jsonData);
    }
}
