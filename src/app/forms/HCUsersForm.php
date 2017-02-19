<?php

namespace interactivesolutions\honeycombacl\forms;

class HCUsersForm
{
    // name of the form
    protected $formID = 'users';

    // is form multi language
    protected $multiLanguage = 0;

    /**
     * Creating form
     *
     * @param bool $edit
     * @return array
     */
    public function createForm ($edit = false)
    {
        $form = [
            'storageURL' => route ('admin.api.users'),
            'buttons'    => [
                [
                    "class" => "col-centered",
                    "label" => trans ('HCCoreUI::core.button.submit'),
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
                    "type"            => "singleLine",
                    "fieldID"         => "password",
                    "label"           => trans ("HCACL::users.password"),
                    "required"        => 1,
                    "requiredVisible" => 1,
                ]
            ],
        ];

        if ($this->multiLanguage)
            $form['availableLanguages'] = []; //TOTO implement honeycomb-languages package

        if (!$edit)
            return $form;

        //Make changes to edit form if needed
        $form['structure'] = array_merge($form['structure'],

            [[
                "type"            => "singleLine",
                "fieldID"         => "activated_at",
                "label"           => trans ("HCACL::users.activated_at"),
                "required"        => 0,
                "requiredVisible" => 0,
                "readonly"        => 1,
            ], [
                "type"            => "singleLine",
                "fieldID"         => "remember_token",
                "label"           => trans ("HCACL::users.remember_token"),
                "required"        => 0,
                "requiredVisible" => 0,
                "readonly"        => 1,
            ], [
                "type"            => "singleLine",
                "fieldID"         => "last_login",
                "label"           => trans ("HCACL::users.last_login"),
                "required"        => 0,
                "requiredVisible" => 0,
                "readonly"        => 1,
            ], [
                "type"            => "singleLine",
                "fieldID"         => "last_visited",
                "label"           => trans ("HCACL::users.last_visited"),
                "required"        => 0,
                "requiredVisible" => 0,
                "readonly"        => 1,
            ], [
                "type"            => "singleLine",
                "fieldID"         => "last_activity",
                "label"           => trans ("HCACL::users.last_activity"),
                "required"        => 0,
                "requiredVisible" => 0,
                "readonly"        => 1,
            ]]);

        return $form;
    }
}