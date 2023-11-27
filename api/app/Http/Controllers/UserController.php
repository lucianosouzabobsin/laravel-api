<?php

namespace App\Http\Controllers;

use App\Rules\UserExists;
use App\Rules\UserGroupExists;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\AuthUser;
use App\Services\UserGroupHasAbilitiesService;
use App\Services\UserGroupService;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    protected $authUserService;
    protected $userGroupService;
    protected $userGroupHasAbilitiesService;

    public function __construct(
        AuthUser $authUserService,
        UserGroupService $userGroupService,
        UserGroupHasAbilitiesService $userGroupHasAbilitiesService
    )
    {
        $this->authUserService = $authUserService;
        $this->userGroupService = $userGroupService;
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
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'user_group_id' => [
                'required',
                new UserGroupExists(
                    $request->all(),
                    $this->userGroupService,
                    $this->userGroupHasAbilitiesService
                )
            ],
            'email' => [
                'required',
                'string',
                'max:255',
                new UserExists($request->all(), $this->authUserService)
            ],
            'password' => ['required', 'string', 'min:8'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        return $this->authUserService->register($request);
    }

    /**
     * Handles Login Request
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (auth()->attempt($credentials)) {
            return $this->authUserService->login();
        } else {
            return response()->json(['error' => 'UnAuthorised'], 401);
        }
    }

    /**
     * Returns Authenticated User isLoggedIn
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function isLoggedIn()
    {
        if (Auth::guard('sanctum')->check()) {
            return response()->json(['message' =>  "Authenticated"], 200);
        }

        return response()->json(['message' =>  "Unauthenticated"], 401);
    }

    /**
     * Returns Authenticated User Details
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function details()
    {
        return response()->json(['user' => auth()->user()], 200);
    }
}
