<?php

namespace App\Http\Controllers;

use App\Rules\AbilityExistsRules;
use App\Services\AbilityService;
use App\Services\UserGroupHasAbilitiesService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserGroupHasAbilitiesController extends Controller
{
    protected $userGroupHasAbilitiesService;
    protected $abilityService;

    public function __construct(UserGroupHasAbilitiesService $userGroupHasAbilitiesService, AbilityService $abilityService)
    {
        $this->userGroupHasAbilitiesService = $userGroupHasAbilitiesService;
        $this->abilityService = $abilityService;
    }


    /**
     * Return list Users Groups
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(Request $request)
    {
        $userGroupHasAbilities = $this->userGroupHasAbilitiesService->getAll($request->all());

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
        $inputs['abilities_ids'] = explode(",", $inputs['abilities_ids']);

        $validator = Validator::make($inputs, [
            'user_group_id' => ['required', 'numeric'],
            'abilities_ids' => [
                'required',
                new AbilityExistsRules($inputs, $this->abilityService)
            ],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $userGroupHasAbilities = $this->userGroupHasAbilitiesService->make($inputs);

        return response()->json($userGroupHasAbilities, 201);
    }
}
