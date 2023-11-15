<?php

namespace App\Http\Controllers;

use App\Rules\AbilityRules;
use App\Rules\AbilitySuperAdminRules;
use App\Services\AbilityService;
use App\Services\ModuleActionService;
use App\Services\ModuleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AbilityController extends Controller
{
    protected $abilityService;
    protected $moduleService;
    protected $moduleActionService;

    public function __construct(
        AbilityService $abilityService,
        ModuleService $moduleService,
        ModuleActionService $moduleActionService
    )
    {
        $this->abilityService = $abilityService;
        $this->moduleService = $moduleService;
        $this->moduleActionService = $moduleActionService;
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
        $inputs = $request->all();

        $validator = Validator::make($inputs, [
            'id' => [
                'required',
                new AbilitySuperAdminRules($inputs, $this->abilityService)
            ]
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $ability = $this->abilityService->active($inputs['id']);

        return response()->json($ability, 201);
    }

    /**
     * Handles Registration Request
     */
    public function run()
    {
        $path = base_path('routes/api.php');
        $contents = file_get_contents($path);

        // Use uma expressão regular para encontrar as palavras entre ->middleware(['auth:sanctum', 'abilities: e ']);
        preg_match_all("/\->middleware\(\['auth:sanctum', 'ability:(.*?)'\]\);/", $contents, $matches);

        $newModules = 0;
        $newActions = 0;
        $newAbilities = 0;

        // $matches[1] conterá as palavras capturadas
        foreach ($matches[1] as $match) {

            if ($match == $this->abilityService::ALL_ABILITY) {
                continue;
            }

            list($moduleMatch, $actionMatch) = explode(":", $match);

            $module = $this->moduleService->findBy([
                ['name', '=', $moduleMatch],
            ]);


            if (is_null($module)) {
                $module = $this->moduleService->make([
                    'name' => $moduleMatch,
                    'nickname' => ucfirst($moduleMatch),
                    'description' => ucfirst($moduleMatch)
                ]);

                $newModules ++;
            }

            $action = $this->moduleActionService->findBy([
                ['action', '=', $actionMatch],
            ]);

            if (is_null($action)) {
                $action = $this->moduleActionService->make([
                    'action' => $actionMatch
                ]);

                $newActions ++;
            }

            $ability = $this->abilityService->findBy([
                ['ability', '=', $match],
            ]);

            if (is_null($ability)) {
                $ability = $this->abilityService->make([
                    'module_id' =>$module['id'],
                    'module_action_id' =>$action['id'],
                    'ability' => $match
                ]);

                $newAbilities ++;
            }
        }

        return response()->json([
            'result' => [
                'new modules' => $newModules,
                'new actions' => $newActions,
                'new abilities' => $newAbilities
            ]
        ], 201);
    }
}
