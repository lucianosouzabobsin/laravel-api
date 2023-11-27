<?php

namespace App\Http\Controllers;

use App\Rules\UserGroupExists;
use App\Rules\UserGroupSuperAdminRules;
use App\Services\UserGroupHasAbilitiesService;
use App\Services\UserGroupService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserGroupController extends Controller
{
    protected $userGroupService;
    protected $userGroupHasAbilitiesService;

    public function __construct(
        UserGroupService $userGroupService,
        UserGroupHasAbilitiesService $userGroupHasAbilitiesService
    )
    {
        $this->userGroupService = $userGroupService;
        $this->userGroupHasAbilitiesService = $userGroupHasAbilitiesService;
    }


    /**
     * Handles List Request
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(Request $request)
    {
        $inputs = $request->all();

        $filters = isset($inputs['filters']) ? $inputs['filters'] : [];
        $options = isset($inputs['options']) ? $inputs['options'] : [];

        $modules = $this->userGroupService->list($filters, $options);

        return response()->json($modules, 201);
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
            'name' => [
                'required',
                'string',
                'max:30',
                new UserGroupExists(
                    $request->all(),
                    $this->userGroupService,
                    $this->userGroupHasAbilitiesService
                )
            ],
            'description' => ['required', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $module = $this->userGroupService->make($inputs);

        return response()->json($module, 201);
    }

    /**
     * Handles Registration Request
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $inputs = $request->all();

        $validator = Validator::make($inputs, [
            'id' => ['required'],
            'description' => 'required|string|max:255',
            'active' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $module = $this->userGroupService->update($inputs);

        return response()->json($module, 201);
    }

    /**
     * Handles Registration Request
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function active(Request $request)
    {
        $inputs = $request->all();

        $validator = Validator::make($inputs, [
            'id' => [
                'required',
                new UserGroupSuperAdminRules($inputs, $this->userGroupService)
            ]
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $module = $this->userGroupService->active($inputs['id']);

        return response()->json($module, 201);
    }
}
