<?php

namespace App\Rules;

use App\Services\UserGroupService;
use Illuminate\Contracts\Validation\Rule;

class UserGroupSuperAdminRules implements Rule
{
    private $request;
    protected $userGroupService;
    protected $messageError;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($request, UserGroupService $userGroupService)
    {
        $this->request = $request;
        $this->userGroupService = $userGroupService;
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
        $userGroup = $this->userGroupService->find($id);

        if (!isset($userGroup['name'])) {
            $this->messageError = 'The userGroup does not exist.';
            return false;
        }

        if ($userGroup['name'] == $this->userGroupService::SUPER_ADMIN) {
            $this->messageError = 'Super admin userGroup cannot be revoked.';
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
