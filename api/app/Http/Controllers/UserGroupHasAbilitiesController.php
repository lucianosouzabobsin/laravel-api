<?php

namespace App\Http\Controllers;

use App\Rules\UserGroupHasAbilitiesRules;
use App\Services\UserGroupHasAbilitiesService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserGroupHasAbilitiesController extends Controller
{
    protected $userGroupHasAbilitiesService;

    public function __construct(UserGroupHasAbilitiesService $userGroupHasAbilitiesService)
    {
        $this->userGroupHasAbilitiesService = $userGroupHasAbilitiesService;
    }


    /**
     * Return list Users Groups
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function list()
    {
        $userGroupHasAbilities = $this->userGroupHasAbilitiesService->getAll();

        return response()->json($userGroupHasAbilities, 201);
    }

    /**
     * Handles Registration Request
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $inputs = $request->all();

        $validator = Validator::make($inputs, [
            'user_group_id' => ['required', 'numeric'],
            'abilities_ids' => [
                'required',
                'array',
                new UserGroupHasAbilitiesRules($inputs, $this->userGroupHasAbilitiesService)
            ],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $userGroupHasAbilities = $this->userGroupHasAbilitiesService->make($inputs);

        return response()->json($userGroupHasAbilities, 201);
    }
}
