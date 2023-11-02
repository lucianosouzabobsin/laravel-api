<?php

namespace App\Rules;

use App\Services\ModuleActionService;
use Illuminate\Contracts\Validation\Rule;

class ModuleActionExists implements Rule
{
    private $request;
    protected $moduleActionService;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($request, ModuleActionService $moduleActionService)
    {
        $this->request = $request;
        $this->moduleActionService = $moduleActionService;
    }


    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $id = isset($this->request['id'])?$this->request['id']:null;
        $action = $this->request['action'];

        return $this->moduleActionService->exists($id, $action) == false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The action has already been taken.';
    }
}
