<?php

namespace App\Rules;

use App\Services\UserGroupHasAbilitiesService;
use Illuminate\Contracts\Validation\Rule;

class UserGroupHasAbilitiesRules implements Rule
{
    private $request;
    protected $userGroupHasAbilitiesService;
    protected $messageError;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($request, UserGroupHasAbilitiesService $userGroupHasAbilitiesService)
    {
        $this->request = $request;
        $this->userGroupHasAbilitiesService = $userGroupHasAbilitiesService;
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
        $userGroupId = $this->request['user_group_id'];
        $abilitiesIds = $this->request['abilities_ids'];

        foreach ($abilitiesIds as $abilityId) {
            if ($this->userGroupHasAbilitiesService->exists($userGroupId, $abilityId)) {
                $this->messageError = sprintf('The ability %s already exists for the user group.', $abilityId);
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
