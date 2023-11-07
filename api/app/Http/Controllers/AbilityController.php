<?php

namespace App\Http\Controllers;

use App\Rules\AbilityRules;
use App\Services\AbilityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AbilityController extends Controller
{
    protected $abilityService;

    public function __construct(AbilityService $abilityService)
    {
        $this->abilityService = $abilityService;
    }


    /**
     * Return list abilities
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function list()
    {
        $moduleActionPermission = $this->abilityService->getAll();

        return response()->json($moduleActionPermission, 201);
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
        $inputs['ability'] = $this->abilityService->getName($inputs);

        $validator = Validator::make($inputs, [
            'module_id' => ['required'],
            'module_action_id' => ['required'],
            'ability' => [
                'required',
                'string',
                new AbilityRules($inputs, $this->abilityService)
            ],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $ability = $this->abilityService->make($inputs);

        return response()->json($ability, 201);
    }


    /**
     * Handles Registration Request
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function active(Request $request)
    {
        try {
            $inputs = $request->all();

            $validator = Validator::make($inputs, [
                'id' => ['required']
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $ability = $this->abilityService->active($inputs['id']);

            return response()->json($ability, 201);
        } catch (\Throwable $th) {
            $error = 'Bad request';

            return response()->json([
                'error' => $error,
                'description_error' => $th->getMessage()
            ], 404);
        }
    }
}
