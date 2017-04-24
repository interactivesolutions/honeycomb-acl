<?php

namespace interactivesolutions\honeycombacl\app\forms;

class HCUsersRegisterForm
{
    // name of the form
    protected $formID = 'users-register';

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
            'storageURL' => route ('auth.register'),
            'buttons'    => [
                [
                    "class" => "col-centered",
                    "label" => trans ('HCCoreUI::core.button.register'),
                    "type"  => "submit",
                ],
            ],
            'structure'  => [
                [
                    "type"            => "singleLine",
                    "fieldID"         => "email",
                    "label"           => trans ("HCACL::users.email"),
                    "required"        => 1,
                    "requiredVisible" => 1,
                ], [
                    "type"            => "password",
                    "fieldID"         => "password",
                    "label"           => trans ("HCACL::users.password"),
                    "required"        => 1,
                    "requiredVisible" => 1,
                ],
            ],
        ];

        return $form;
    }
}