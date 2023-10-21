<?php

namespace App\Http\Controllers;

use App\Services\ModuleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ModuleController extends Controller
{
    protected $moduleService;

    public function __construct(ModuleService $moduleService)
    {
        $this->moduleService = $moduleService;
    }


    /**
     * Return list Modules
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function list()
    {
        try {

            $modules = $this->moduleService->getAll();

            return response()->json($modules, 201);
        } catch (\Throwable $th) {
            $error = 'Bad request';

            return response()->json([
                'error' => $error,
                'description_error' => $th->getMessage()
            ], 404);
        }
    }

    /**
     * Handles Registration Request
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        try {
            $inputs = $request->all();

            $validator = Validator::make($inputs, [
                'name' => 'required|string|max:30',
                'description' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $module = $this->moduleService->make($inputs);

            return response()->json($module, 201);
        } catch (\Throwable $th) {
            $error = 'Bad request';

            return response()->json([
                'error' => $error,
                'description_error' => $th->getMessage()
            ], 404);
        }
    }

    /**
     * Handles Registration Request
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        try {
            $inputs = $request->all();

            $validator = Validator::make($inputs, [
                'id' => ['required'],
                'name' => 'required|string|max:30',
                'description' => 'required|string|max:255',
                'active' => 'required|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $module = $this->moduleService->update($inputs);

            return response()->json($module, 201);
        } catch (\Throwable $th) {
            $error = 'Bad request';

            return response()->json([
                'error' => $error,
                'description_error' => $th->getMessage()
            ], 404);
        }
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

            $module = $this->moduleService->active($inputs['id']);

            return response()->json($module, 201);
        } catch (\Throwable $th) {
            $error = 'Bad request';

            return response()->json([
                'error' => $error,
                'description_error' => $th->getMessage()
            ], 404);
        }
    }
}