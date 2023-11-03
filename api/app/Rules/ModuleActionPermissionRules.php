<?php

namespace App\Rules;

use App\Services\ModuleActionPermissionService;
use Illuminate\Contracts\Validation\Rule;

class ModuleActionPermissionRules implements Rule
{
    private $request;
    protected $moduleActionPermissionService;
    protected $messageError;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($request, ModuleActionPermissionService $moduleActionPermissionService)
    {
        $this->request = $request;
        $this->moduleActionPermissionService = $moduleActionPermissionService;
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
        $name = $this->request['name'];

        list($module, $action) = explode($this->moduleActionPermissionService::NAME_SEPARATOR, $name);

        if ($module == "" && $action == "") {
            $this->messageError = 'The module and action do not exist.';
            return false;
        }

        if ($module == "") {
            $this->messageError = 'The module do not exist.';
            return false;
        }

        if ($action == "") {
            $this->messageError = 'The action do not exist.';
            return false;
        }

        if ($this->moduleActionPermissionService->exists($id, $name)) {
            $this->messageError = 'The name has already been taken.';
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->messageError;
    }
}
