<?php

namespace Tests\Feature\Service;

use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\AuthUser;
use Illuminate\Http\Request;
use Tests\TestCase;

class AuthUserTest extends TestCase
{
    /**
     * Testa o registro para um erro 404.
     *
     * @return void
     */
    public function testRegisterError()
    {
        $userRepositoryMock = $this->createMock(UserRepositoryInterface::class);

        $authUserService = new AuthUser($userRepositoryMock);

        $request = Request::create('/register', 'POST', [
            'campoerrado' => 'Test User',
            'campoerrado2' => 'test@example.com',
            'campoerrado3' => 'password123',
        ]);

        $response = $authUserService->register($request);

        $jsonData = json_decode($response->getContent(), true);

        $this->assertEquals('Bad request', $jsonData['error']);
        $this->assertEquals(404, $response->getStatusCode());
    }
}
