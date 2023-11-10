<?php

namespace App\Rules;

use App\Services\UserGroupHasAbilitiesService;
use App\Services\UserGroupService;
use Illuminate\Contracts\Validation\Rule;

class UserGroupExists implements Rule
{
    private $request;
    protected $userGroupService;
    protected $userGroupHasAbilitiesService;
    protected $messageError;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($request,
        UserGroupService $userGroupService,
        UserGroupHasAbilitiesService $userGroupHasAbilitiesService
    )
    {
        $this->request = $request;
        $this->userGroupService = $userGroupService;
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
        $id = isset($this->request['id'])?$this->request['id']:null;
        $name = isset($this->request['name'])?$this->request['name']:null;
        $userGroupId = isset($this->request['user_group_id'])?$this->request['user_group_id']:null;

        if (!is_null($userGroupId)) {
            $userGroup = $this->userGroupService->find($userGroupId);

            if (!isset($userGroup['id'])) {
                $this->messageError = 'The userGroup does not exist.';
                return false;
            }

            $filters['user_group_id'] = $userGroupId;
            $userGroupAbilities = $this->userGroupHasAbilitiesService->getAll($filters);

            if (empty($userGroupAbilities)) {
                $this->messageError = 'The userGroup does not have abilities.';
                return false;
            }

        } else {
            $userGroup = $this->userGroupService->exists($id, $name);

            if (isset($userGroup['name'])) {
                $this->messageError = 'The name has already been taken.';
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
