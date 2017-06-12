<?php

namespace interactivesolutions\honeycombacl\app\forms;

class HCPasswordResetForm
{
    // name of the form
    protected $formID = 'password-reset';

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
            "storageURL" => route('users.password.reset.post'),
            "buttons"    => [
                [
                    "class" => "col-centered",
                    "label" => trans('HCACL::users.passwords.reset_button'),
                    "type"  => "submit",
                ],
            ],
            "structure"  => [
                [
                    "type"            => "email",
                    "fieldID"         => "email",
                    "label"           => trans('HCACL::users.login.email'),
                    "required"        => 1,
                    "requiredVisible" => 1,
                    "maxLength"       => "197",
                ],
                [
                    "type"            => "password",
                    "fieldID"         => "password",
                    "label"           => trans('HCACL::users.passwords.new'),
                    "required"        => 1,
                    "requiredVisible" => 1,
                    "maxLength"       => "197",
                ],
                [
                    "type"            => "password",
                    "fieldID"         => "password_confirmation",
                    "label"           => trans('HCACL::users.passwords.new_again'),
                    "required"        => 1,
                    "requiredVisible" => 1,
                    "maxLength"       => "197",
                ],
                [
                    "type"            => "singleLine",
                    "fieldID"         => "token",
                    "hidden"          => 1,
                    "required"        => 1,
                    "requiredVisible" => 1,
                    "maxLength"       => "255",
                    "value"           => request()->input('token'),
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