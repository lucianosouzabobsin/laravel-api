<?php

namespace App\Rules;

use App\Services\AuthUser;
use Illuminate\Contracts\Validation\Rule;

class UserExists implements Rule
{
    private $request;
    protected $authUserService;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($request, AuthUser $authUserService)
    {
        $this->request = $request;
        $this->authUserService = $authUserService;
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
        $email = $this->request['email'];

        return $this->authUserService->exists($id, $email) == false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The e-mail has already been taken.';
    }
}
