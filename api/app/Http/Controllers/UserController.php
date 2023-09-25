<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\AuthUser;

class UserController extends Controller
{
    protected $authUserService;

    public function __construct(AuthUser $authUserService)
    {
        $this->authUserService = $authUserService;
    }

    /**
     * Handles Registration Request
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|unique:users|max:255',
                'password' => 'required|string|min:8',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            return $this->authUserService->register($request);

        } catch (\Throwable $th) {
            return response()->json([
                'error' =>  'Bad request'
            ], 404);
        }
    }

    /**
     * Handles Login Request
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        try {
            $credentials = [
                'email' => $request->email,
                'password' => $request->password
            ];

            if (auth()->attempt($credentials)) {
                return $this->authUserService->login();
            } else {
                return response()->json(['error' => 'UnAuthorised'], 401);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'error' =>  'Bad request'
            ], 404);
        }
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
