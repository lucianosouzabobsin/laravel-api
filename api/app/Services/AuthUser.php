<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Carbon\Carbon;

class AuthUser
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Handles Registration Request
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $user = $this->userRepository->make([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $this->createToken($user);

        return response()->json(['token' => $token], 201);
    }

    /**
     * Handles Login Request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $user = $this->userRepository->findUserAuth();

        $user->tokens()->delete();

        $token = $this->createToken($user);

        return response()->json(['token' => $token], 201);
    }

    /**
     * Verifica se ja existe o módulo
     *
     * @return array
    */
    public function exists(int $id, string $email)
    {
        return $this->userRepository->exists($id, $email);
    }

    /**
     * Create Token
     *
     * @param User $user
     * @return String
     */
    private function createToken(User $user) {
        return $user->createToken(
            $user->name.'_'.Carbon::now(),
            ['*'],
            Carbon::now()->addHour()
        )->plainTextToken;
    }
}
