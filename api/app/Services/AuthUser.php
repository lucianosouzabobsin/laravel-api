<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Repositories\Contracts\UserGroupRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use Carbon\Carbon;

class AuthUser
{
    protected $userRepository;
    protected $userGroupRepository;
    protected $userGroupHasAbilitiesService;

    public function __construct(
        UserRepositoryInterface $userRepository,
        UserGroupRepositoryInterface $userGroupRepository,
        UserGroupHasAbilitiesService $userGroupHasAbilitiesService
    ) {
        $this->userRepository = $userRepository;
        $this->userGroupRepository = $userGroupRepository;
        $this->userGroupHasAbilitiesService = $userGroupHasAbilitiesService;
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
            'user_group_id' => $request->user_group_id,
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
    public function exists(?int $id, string $email)
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
        $userGroupAbilities = $this->getAbilities($user);

        return $user->createToken(
            $user->name.'_'.Carbon::now(),
            $userGroupAbilities,
            Carbon::now()->addHour()
        )->plainTextToken;
    }

    /**
     * Create Token
     *
     * @param User $user
     * @return Array
     */
    private function getAbilities(User $user) {

        $filters['user_group_id'] = $user->user_group_id;

        $userGroup = $this->userGroupRepository->find($user->user_group_id);
        $userGroupAbilities = $this->userGroupHasAbilitiesService->getAll($filters);

        return $userGroupAbilities[$userGroup->name]['text'];
    }
}
