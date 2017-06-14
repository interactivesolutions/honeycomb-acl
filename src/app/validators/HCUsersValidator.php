<?php

namespace interactivesolutions\honeycombacl\app\validators;

use interactivesolutions\honeycombcore\http\controllers\HCCoreFormValidator;

class HCUsersValidator extends HCCoreFormValidator
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array`
     */
    protected function rules()
    {
//        return [
//            'email'    => 'required|email|unique:hc_users,email',
//            'password' => 'required',
//        ];

        switch ( $this->methodType() ) {
            case 'POST':
                return $this->createRules();

            case 'PUT':
                return $this->updateRules();

            default:
                return ['no_rules' => 'required'];
        }
    }

    /**
     * Rules for item creation
     *
     * @return array
     */
    private function createRules()
    {
        return [
            'email'    => 'required|email|unique:hc_users,email|min:5',
//            'nickname' => 'required|unique:oc_users_personal_data,nickname|min:3',
            'password' => 'required|min:5',
//            'roles'    => 'required',
        ];
    }

    /**
     * Rules when item is updating
     *
     * @return array
     */
    private function updateRules()
    {
        return [
            'email'                 => 'required|email|min:5|unique:hc_users,email,' . $this->id,
//            'nickname'              => 'required|min:3|unique:oc_users_personal_data,nickname,' . $this->recordId . ',user_id',
//            'roles'                 => 'required',
            'old_password'          => 'min:5',
            'password'              => 'required_with:old_password|min:5|confirmed',
            'password_confirmation' => 'required_with:password|min:5',
        ];
    }

    /**
     * Custom messages
     *
     * @return array
     */
    protected function messages()
    {
        // field.condition => translation
        return [
            'nickname.required'  => trans('HCACL::users.validator.nickname.required'),
            'nickname.unique'    => trans('HCACL::users.validator.nickname.unique'),
            'nickname.min'       => trans('HCACL::users.validator.nickname.min', ['count' => 3]),
            'email.required'     => trans('HCACL::users.validator.email.required'),
            'email.unique'       => trans('HCACL::users.validator.email.unique'),
            'email.min'          => trans('HCACL::users.validator.email.min', ['count' => 5]),
            'password.required'  => trans('HCACL::users.validator.password.required'),
            'password.min'       => trans('HCACL::users.validator.password.min', ['count' => 5]),
            'password.confirmed' => trans('HCACL::users.validator.password.confirmed'),
            'roles.required'     => trans('HCACL::users.validator.roles.required'),
        ];
    }
}