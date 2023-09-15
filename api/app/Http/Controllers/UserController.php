<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
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

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $token = $user->createToken(
                $user->name.'_'.Carbon::now(),
                ['*'],
                Carbon::now()->addHour()
            )->plainTextToken;

            return response()->json(['token' => $token], 201);
        } catch (\Throwable $th) {
            $error = 'Bad request';

            return response()->json([
                'error' =>  $error
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

                $user = User::find(auth()->user()->id);

                $user->tokens()->delete();

                $token = $user->createToken(
                            $user->name.'_'.Carbon::now(),
                            ['*'],
                            Carbon::now()->addHour()
                        )->plainTextToken;

                return response()->json(['token' => $token], 201);
            } else {
                return response()->json(['error' => 'UnAuthorised'], 401);
            }

        } catch (\Throwable $th) {
            $error = 'Bad request';

            return response()->json([
                'error' =>  $error
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
