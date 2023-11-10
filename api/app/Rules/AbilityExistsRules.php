<?php

namespace App\Rules;

use App\Services\AbilityService;
use Illuminate\Contracts\Validation\Rule;

class AbilityExistsRules implements Rule
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
        $abilitiesIds = $this->request['abilities_ids'];

        foreach ($abilitiesIds as $abilityId) {
            $ability = $this->abilityService->find($abilityId);

            if (!$ability) {
                $this->messageError = sprintf('The ability %s does not exist.', $abilityId);
                return false;
            }

            if ($ability['ability'] == $this->abilityService::ALL_ABILITY) {
                $this->messageError = sprintf('Superadmin ability cannot be added.');
                return false;
            }
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
