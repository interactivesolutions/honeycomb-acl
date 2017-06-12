<?php

return [
    'page_title'    => 'Users',
    'title'         => 'Users',
    'name'          => 'Users',
    'email'         => 'E-mail',
    'firstname'     => 'Firstname',
    'lastname'      => 'Lastname',
    'sex'           => 'Sex',
    'birthdate'     => 'Birthdate',
    'street'        => 'Street',
    'house'         => 'House',
    'apartment'     => 'Apartment',
    'postcode'      => 'Postcode',
    'city'          => 'City',
    'municipality'  => 'Municipality',
    'companyName'   => 'Company Name',
    'companyCode'   => 'Company Code',
    'companyVat'    => 'Company VAT',
    'role_groups'   => 'Roles',
    'male'          => 'Male',
    'female'        => 'Female',
    'provider'      => 'Provider',
    'active'        => 'Is activated?',
    'active_true'   => 'Yes',
    'last_login'    => 'Last login',
    'last_activity' => 'Last activity',

    'tabs'      => [
        'main'     => 'Main',
        'personal' => 'Personal',
        'info'     => 'Info',
    ],

    /*
     * Personal data
     */
    'photo'     => 'Photo',
    'nickname'  => 'Nickname',
    'full_name' => 'Full name',
    'gender'    => 'Gender',
    'phone'     => 'Phone',
    'avatar'    => 'Avatar',

    'login' => [
        'title'       => 'Sign in to start your session',
        'sign-in'     => 'Sign in',
        'email'       => 'E-mail',
        'password'    => 'Password',
        're-password' => 'Retype password',
    ],

    'register' => [
        'title'          => 'Register a new account',
        'sign-up'        => 'Sign up',
        'email'          => 'E-mail',
        'password'       => 'Password',
        'password_again' => 'Password confirmation',
    ],

    'activation' => [
        'title'             => 'Account activation',
        'info'              => 'You have to active your account.',
        'activate'          => 'Activate',
        'token_not_exists'  => 'Token does not exists!',
        'token_expired'     => 'Token is expired!',
        'back_to_main'      => 'Back to main page',
        'bad_token'         => 'There is a problem with a given token. Please check your email for correct token',
        'user_not_found'    => 'Something went wrong with user account, please try again to login or register.',
        'check_email'       => 'Check your email for activation link',
        'resent_activation' => 'We have resent a new activation link for your account. Please check your email.',
        'activate_account'  => 'We have sent to your given email address an activation link. Please check your email and activate your account.',

        'mail' => [
            'subject' => 'Account confirmation',
            'from'    => 'Administrator',
            'email'   => 'In order to login you have to verify your email address <strong>:email</strong>',
            'link'    => 'Please click given link to activate your account <a href=":link">:link</a>',
        ],
    ],

    'connect_with_fb' => 'Connect with <strong>Facebook</strong>',

    'facebook' => [
        'title'  => 'Facebook',
        'errors' => [
            'email'        => 'Email option is required!',
            'user_friends' => 'Friends option is required!',
        ],
    ],

    'errors' => [
        'login'            => 'Blogi prisijungimo duomenys!',
        'to_many_attempts' => 'Too many login attempts. Please try again in :seconds seconds.',
        'nickname_exists'  => 'Nickname already exists!',
        'facebook'         => 'Please try again.',
        'badOldPass'       => 'Wrong old password!',
    ],

    'send_welcome_email' => 'Send welcome email',
    'send_password'      => 'Send password',

    'registered' => [
        'success'       => 'Your new account',
        'administrator' => 'Administrator',
        'nickname'      => 'Welcome, <b>:nickname</b>',
        'email'         => 'Now you can login with your email: <b>:email</b>',
        'loginpage'     => 'Go to login page: <a href=":loginpage">Login</a>',
        'password'      => 'Your password is <b>:password</b> don\'t share it with others!',
    ],

    'passwords' => [
        "forgot_password" => "Forgot password?",
        "email"           => 'Email',
        "can_login"       => "Now you can log in <a href=':url'>Log in</a>",
        "reset_view"      => "Password reset",
        "reset_button"    => "Reset password",
        "remind_button"   => "Send remind",
        "new"             => "New password",
        "new_again"       => "New password again",
        "old"             => "Old password",
        "click_here"      => "Click this link if you want to reset the password : <a href=':link'>Reset</a>",
        "remind"          => "Send me password reset link",
        "password"        => "Passwords must be at least six characters and match the confirmation.",
        "user"            => "We can't find a user with that e-mail address.",
        "token"           => "This password reset token is invalid.",
        "sent"            => "We have sent your password reset link to you! Check email!",
        "reset"           => "Your password has been reset!",
        "subject"         => "Your password reset link.",
    ],

    'admin' => [
        'menu' => [
            'logout'  => 'Logout',
            'roles'   => 'Roles: ',
            'online'  => 'Online',
            'profile' => 'Profile',
        ],

        'member_since' => 'Member since :date',
    ],
];
