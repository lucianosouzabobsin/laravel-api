<?php

namespace App\Rules;

use App\Services\AbilityService;
use Illuminate\Contracts\Validation\Rule;

class AbilityRules implements Rule
{
    private $request;
    protected $abilityService;
    protected $messageError;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($request, AbilityService $abilityService)
    {
        $this->request = $request;
        $this->abilityService = $abilityService;
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
        $ability = $this->request['ability'];

        if ($ability != $this->abilityService::ALL_ABILITY) {

            list($module, $action) = explode($this->abilityService::NAME_SEPARATOR, $ability);

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
        }


        if ($this->abilityService->exists($id, $ability)) {
            $this->messageError = 'The ability has already been taken.';
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
