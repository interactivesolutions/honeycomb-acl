<?php

namespace interactivesolutions\honeycombacl\app\forms;

use interactivesolutions\honeycombacl\app\models\acl\Roles;

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
    public function createForm(bool $edit = false)
    {
        $this->getRolesForUserCreation();

        $rolesStructure = [
            "type"            => 'checkBoxList',
            "fieldID"         => 'roles',
            "label"           => trans("HCACL::users.role_groups"),
            "required"        => 1,
            "requiredVisible" => 1,
            "options"         => $this->getRolesForUserCreation(),
        ];

        $form = [
            'storageURL' => route('admin.api.users'),
            'buttons'    => [
                [
                    "class" => "col-centered",
                    "label" => trans('HCTranslations::core.buttons.submit'),
                    "type"  => "submit",
                ],
            ],
            'structure'  => [
                $rolesStructure,
                [
                    "type"            => "email",
                    "fieldID"         => "email",
                    "label"           => trans("HCACL::users.email"),
                    "required"        => 1,
                    "requiredVisible" => 1,
                ],
                [
                    "type"            => "password",
                    "fieldID"         => "password",
                    "label"           => trans("HCACL::users.register.password"),
                    "required"        => 1,
                    "requiredVisible" => 1,
                ],
                formManagerYesNo('checkBoxList', 'is_active', trans("HCACL::users.active"), 0, 0, null, false),
                formManagerYesNo('checkBoxList', 'send_welcome_email', trans("HCACL::users.send_welcome_email"), 0, 0, null, false),
                formManagerYesNo('checkBoxList', 'send_password', trans("HCACL::users.send_password"), 0, 0, null, false),
            ],
        ];

        if( $this->multiLanguage )
            $form['availableLanguages'] = []; //TOTO implement honeycomb-languages package

        if( ! $edit )
            return $form;

        //Make changes to edit form if needed

        $form['structure'] = [];

        $form['structure'] = array_merge($form['structure'], [
            $rolesStructure,
            [
                "type"            => "email",
                "fieldID"         => "email",
                "label"           => trans("HCACL::users.email"),
                "required"        => 1,
                "requiredVisible" => 1,
            ],
            [
                "type"            => "password",
                "fieldID"         => "old_password",
                "label"           => trans('HCACL::users.passwords.old'),
                "editType"        => 0,
                "required"        => 0,
                "requiredVisible" => 0,
                "properties"      => [
                    "strength" => "1" // case 0: much, case 1: 4 symbols, case 2: 6 symbols
                ],
            ],
            [
                "type"            => "password",
                "fieldID"         => "password",
                "label"           => trans('HCACL::users.passwords.new'),
                "editType"        => 0,
                "required"        => 0,
                "requiredVisible" => 0,
                "properties"      => [
                    "strength" => "1" // case 0: much, case 1: 4 symbols, case 2: 6 symbols
                ],
            ],
            [
                "type"            => "password",
                "fieldID"         => "password_confirmation",
                "label"           => trans('HCACL::users.passwords.new_again'),
                "editType"        => 0,
                "required"        => 0,
                "requiredVisible" => 0,
                "properties"      => [
                    "strength" => "1" // case 0: much, case 1: 4 symbols, case 2: 6 symbols
                ],
            ],
            formManagerYesNo('checkBoxList', 'is_active', trans("HCACL::users.active"), 0, 0, null, false),
            [
                "type"            => "singleLine",
                "fieldID"         => "last_login",
                "label"           => trans("HCACL::users.last_login"),
                "required"        => 0,
                "requiredVisible" => 0,
                "readonly"        => 1,
            ],
            [
                "type"            => "singleLine",
                "fieldID"         => "last_activity",
                "label"           => trans("HCACL::users.last_activity"),
                "required"        => 0,
                "requiredVisible" => 0,
                "readonly"        => 1,
            ],
            [
                "type"            => "singleLine",
                "fieldID"         => "activated_at",
                "label"           => trans("HCACL::users.activation.activated_at"),
                "required"        => 0,
                "requiredVisible" => 0,
                "readonly"        => 1,
            ],
        ]);

        return $form;
    }

    /**
     * Get roles for user creation. User can give roles that he owns
     *
     * @return array
     */
    public function getRolesForUserCreation()
    {
        $rolesList = [];

        // logged user
        $user = auth()->user();

        if( auth()->check() ) {
            if( $user->isSuperAdmin() ) {
                $rolesList = Roles::select('id', 'name as label')->orderBy('name')->get();
            } else {
                foreach ( $user->roles as $role ) {
                    $rolesList[] = [
                        'id'    => $role->id,
                        'label' => $role->name,
                    ];
                }
            }
        }

        return $rolesList;
    }
}