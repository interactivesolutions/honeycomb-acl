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
        return [
            'email'    => 'required',
            'password' => 'required',
        ];
    }
}