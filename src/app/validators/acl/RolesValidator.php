<?php namespace interactivesolutions\honeycombacl\validators\acl;

use interactivesolutions\honeycombcore\http\controllers\HCCoreFormValidator;

class RolesValidator extends HCCoreFormValidator
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
            'slug' => 'required',
        ];
    }
}