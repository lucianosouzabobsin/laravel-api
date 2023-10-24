<?php

namespace Tests\Feature\Controller;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Testa o registro de um novo usuário.
     *
     * @return void
     */
    public function testRegisterUser()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(201);
    }

    /**
     * Testa o registro de um novo usuário.
     *
     * @return void
     */
    public function testRegisterUserErrorValidatorNoName()
    {
        $response = $this->postJson('/api/register', [
            'email' => 'john.doe@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(422);
    }

    /**
     * Testa o registro de um novo usuário.
     *
     * @return void
     */
    public function testRegisterUserErrorValidatorNoPassword()
    {
        $response = $this->postJson('/api/register', [
            'email' => 'john.doe@example.com',
            'password' => '',
        ]);

        $response->assertStatus(422);
    }

    /**
     * Testa o registro de um novo usuário.
     *
     * @return void
     */
    public function testRegisterUserErrorValidatorBelowStringMinimumPassword()
    {
        $response = $this->postJson('/api/register', [
            'email' => 'john.doe@example.com',
            'password' => '1234567',
        ]);

        $response->assertStatus(422);
    }

    /**
     * Testa o registro de um novo usuário.
     *
     * @return void
     */
    public function testRegisterUserErrorValidatorUniqueEmail()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'password' => 'password123',
        ]);

        $response = $this->postJson('/api/register', [
            'name' => 'John Doe 2',
            'email' => 'john.doe@example.com',
            'password' => 'password1234666',
        ]);

        $response->assertStatus(422);
    }


    /**
     * Testa o login de um usuário existente.
     *
     * @return void
     */
    public function testLoginUser()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Jane Doe',
            'email' => 'jane.doe@example.com',
            'password' => 'password456',
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'jane.doe@example.com',
            'password' => 'password456',
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
        $response = $this->postJson('/api/register', [
            'name' => 'Jane Doe',
            'email' => 'jane.doe@example.com',
            'password' => 'password456',
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'jane.doe@example.com',
            'password' => 'password456qqq',
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
        $register = $this->postJson('/api/register', [
            'name' => 'Jane Doe',
            'email' => 'jane.doe@example.com',
            'password' => 'password456',
        ]);

        $token = $register['token'];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/user');

        $response->assertStatus(200);
    }
}
