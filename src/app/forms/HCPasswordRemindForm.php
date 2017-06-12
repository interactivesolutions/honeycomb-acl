<?php

namespace interactivesolutions\honeycombacl\app\forms;

class HCPasswordRemindForm
{
    // name of the form
    protected $formID = 'password-remind';

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
        $form = [
            "storageURL" => route('users.password.remind.post'),
            "buttons"    => [
                [
                    "class" => "col-centered",
                    "label" => trans('HCTranslations::core.buttons.submit'),
                    "type"  => "submit",
                ],
            ],
            "structure"  => [
                [
                    "type"            => "email",
                    "fieldID"         => "email",
                    "label"           => trans('HCACL::users.login.email'),
                    "editType"        => 0,
                    "required"        => 1,
                    "requiredVisible" => 0,
                    "properties"      => [
                        "style"     => "varchar",
                        "maxlength" => "197",
                    ],
                ],
            ],
        ];

        if( $this->multiLanguage )
            $form['availableLanguages'] = getHCContentLanguages();

        if( ! $edit )
            return $form;

        return $form;
    }
}