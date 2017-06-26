<?php namespace interactivesolutions\honeycombacl\app\validators\users;

use interactivesolutions\honeycombcore\http\controllers\HCCoreFormValidator;

class HCGroupsValidator extends HCCoreFormValidator
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    protected function rules ()
    {
        return [
            'label'      => 'required',

        ];
    }
}