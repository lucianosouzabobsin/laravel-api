<?php

namespace App\Http\Controllers;

use App\Rules\ModuleActionPermissionRules;
use App\Services\ModuleActionPermissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ModuleActionPermissionController extends Controller
{
    protected $moduleActionPermissionService;

    public function __construct(ModuleActionPermissionService $moduleActionPermissionService)
    {
        $this->moduleActionPermissionService = $moduleActionPermissionService;
    }


    /**
     * Return list ModulesActions
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function list()
    {
        $moduleActionPermission = $this->moduleActionPermissionService->getAll();

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
        $inputs['name'] = $this->moduleActionPermissionService->getName($inputs);

        $validator = Validator::make($inputs, [
            'module_id' => ['required'],
            'module_action_id' => ['required'],
            'name' => [
                'required',
                'string',
                new ModuleActionPermissionRules($inputs, $this->moduleActionPermissionService)
            ],
            'description' => 'required|string|max:255',
            'link' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $moduleActionPermission = $this->moduleActionPermissionService->make($inputs);

        return response()->json($moduleActionPermission, 201);
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
            'link' => 'required|string|max:255',
            'active' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $moduleActionPermission = $this->moduleActionPermissionService->update($inputs);

        return response()->json($moduleActionPermission, 201);
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

            $moduleActionPermission = $this->moduleActionPermissionService->active($inputs['id']);

            return response()->json($moduleActionPermission, 201);
        } catch (\Throwable $th) {
            $error = 'Bad request';

            return response()->json([
                'error' => $error,
                'description_error' => $th->getMessage()
            ], 404);
        }
    }
}
