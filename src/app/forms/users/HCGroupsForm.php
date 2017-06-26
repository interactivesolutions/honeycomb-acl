<?php

namespace interactivesolutions\honeycombacl\app\forms\users;

use interactivesolutions\honeycombacl\app\models\HCUsers;

class HCGroupsForm
{
    // name of the form
    protected $formID = 'users-groups';

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
            'storageURL' => route ('admin.api.routes.users.groups'),
            'buttons'    => [
                [
                    "class" => "col-centered",
                    "label" => trans ('HCTranslations::core.buttons.submit'),
                    "type"  => "submit",
                ],
            ],
            'structure'  => [
                [
                    "type"            => "singleLine",
                    "fieldID"         => "label",
                    "label"           => trans ("HCACL::users_groups.label"),
                    "required"        => 1,
                    "requiredVisible" => 1,
                ],
                [
                    "type"            => "dropDownList",
                    "fieldID"         => "users",
                    "label"           => trans ("HCACL::users_groups.users"),
                    "required"        => 1,
                    "requiredVisible" => 1,
                    "options"         => HCUsers::get(),
                    "search"          => [
                        "showNodes" => ['email']
                    ]
                ],
            ],
        ];

        if ($this->multiLanguage)
            $form['availableLanguages'] = getHCContentLanguages ();

        if (!$edit)
            return $form;

        //Make changes to edit form if needed
        $form['structure'][] = [
            "type"            => "dropDownList",
            "fieldID"         => "creator_id",
            "label"           => trans ("HCACL::users_groups.creator_id"),
            "required"        => 1,
            "requiredVisible" => 1,
            "options"         => HCUsers::get(),
            "search"          => [
                "maximumSelectionLength" => 1,
                "minimumSelectionLength" => 1,
                "showNodes" => ['email']
            ]
        ];

        return $form;
    }
}