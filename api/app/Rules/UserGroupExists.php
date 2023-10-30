<?php

namespace App\Rules;

use App\Services\UserGroupService;
use Illuminate\Contracts\Validation\Rule;

class UserGroupExists implements Rule
{
    private $request;
    protected $userGroupService;

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
        $name = $this->request['name'];

        return $this->userGroupService->exists($id, $name) == false;
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
