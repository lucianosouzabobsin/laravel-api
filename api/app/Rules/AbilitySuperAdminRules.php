<?php

namespace App\Rules;

use App\Services\AbilityService;
use Illuminate\Contracts\Validation\Rule;

class AbilitySuperAdminRules implements Rule
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
        $abilities = $this->abilityService->find($id);

        if (!isset($abilities['ability'])) {
            $this->messageError = 'The ability does not exist.';
            return false;
        }

        if ($abilities['ability'] == $this->abilityService::ALL_ABILITY) {
            $this->messageError = 'Super admin ability cannot be revoked.';
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
