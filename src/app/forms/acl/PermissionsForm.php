<?php namespace interactivesolutions\honeycombacl\forms\acl;

use interactivesolutions\honeycombcore\http\controllers\HCCoreFormValidator;

class PermissionsForm extends HCCoreFormValidator
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    protected function rules()
    {
        return [
            'name' => 'required',
'controller' => 'required',
'action' => 'required',

        ];
    }
}