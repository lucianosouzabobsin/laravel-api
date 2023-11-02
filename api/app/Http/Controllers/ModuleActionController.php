<?php

namespace App\Http\Controllers;

use App\Rules\ModuleActionExists;
use App\Services\ModuleActionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ModuleActionController extends Controller
{
    protected $moduleActionService;

    public function __construct(ModuleActionService $moduleActionService)
    {
        $this->moduleActionService = $moduleActionService;
    }


    /**
     * Return list ModulesActions
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function list()
    {
        $modulesActions = $this->moduleActionService->getAll();

        return response()->json($modulesActions, 201);
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
            'action' => [
                'required',
                'string',
                'max:30',
                'regex:/^[a-z]+$/',
                new ModuleActionExists($request->all(), $this->moduleActionService)
            ],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $moduleAction = $this->moduleActionService->make($inputs);

        return response()->json($moduleAction, 201);
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
            'action' => [
                'required',
                'string',
                'max:30',
                'regex:/^[a-z]+$/',
                new ModuleActionExists($request->all(), $this->moduleActionService)
            ],
            'active' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $moduleAction = $this->moduleActionService->update($inputs);

        return response()->json($moduleAction, 201);
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

            $moduleAction = $this->moduleActionService->active($inputs['id']);

            return response()->json($moduleAction, 201);
        } catch (\Throwable $th) {
            $error = 'Bad request';

            return response()->json([
                'error' => $error,
                'description_error' => $th->getMessage()
            ], 404);
        }
    }
}
