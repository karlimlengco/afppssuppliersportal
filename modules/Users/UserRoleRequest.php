<?php

namespace Revlv\Users;

use Revlv\BaseRequest;

class UserRoleRequest extends BaseRequest
{
    /**
     * @var array
     */
    protected $whitelist = [
        'role',
    ];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'role'     => 'required'
        ];
    }

    /**
     * [response description]
     * @param  array  $errors [description]
     * @return [type]         [description]
     */
    public function response(array $errors)
    {
        return $this->redirector->to($this->getRedirectUrl()."#roles")
            ->withInput($this->except($this->dontFlash))
            ->withErrors($errors, $this->errorBag);
    }
}