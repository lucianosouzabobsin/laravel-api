<?php

namespace App\Rules;

use App\Services\ModuleService;
use Illuminate\Contracts\Validation\Rule;

class ModuleExists implements Rule
{
    private $request;
    protected $moduleService;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($request, ModuleService $moduleService)
    {
        $this->request = $request;
        $this->moduleService = $moduleService;
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
        $id = isset($this->request['id'])??null;
        $name = $this->request['name'];

        return $this->moduleService->exists($id, $name) == false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The name has already been taken.';
    }
}
