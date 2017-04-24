<?php

namespace interactivesolutions\honeycombacl\app\forms;

class HCUsersLoginForm
{
    // name of the form
    protected $formID = 'users-login';

    // is form multi language
    protected $multiLanguage = 0;

    /**
     * Creating form
     *
     * @param bool $edit
     * @return array
     */
    public function createForm (bool $edit = false)
    {
        $form = [
            'storageURL' => route ('auth.login'),
            'buttons'    => [
                [
                    "class" => "col-centered btn btn-primary",
                    "label" => trans ('HCCoreUI::core.button.login'),
                    "type"  => "submit",
                ],
            ],
            'structure'  => [
                [
                    "type"            => "singleLine",
                    "fieldID"         => "email",
                    "label"           => trans ("HCACL::users.email"),
                    "required"        => 1,
                ], [
                    "type"            => "password",
                    "fieldID"         => "password",
                    "label"           => trans ("HCACL::users.password"),
                    "required"        => 1,
                ],
            ],
        ];

        return $form;
    }
}