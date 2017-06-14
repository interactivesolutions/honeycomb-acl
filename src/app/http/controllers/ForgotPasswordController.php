<?php

namespace interactivesolutions\honeycombacl\app\http\controllers;

use HCLog;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use interactivesolutions\honeycombcore\http\controllers\HCBaseController;

class ForgotPasswordController extends HCBaseController
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLinkRequestForm()
    {
        return hcview('HCACL::password.remind');
    }

    /**
     * Get the response for a successful password reset link.
     *
     * @param  string  $response
     * @return string
     */
    protected function sendResetLinkResponse($response)
    {
        return HCLog::success('FORGOT-PASS-001', trans($response));
    }

    /**
     * Get the response for a failed password reset link.
     *
     * @param Request $request
     * @param $response
     * @return string;
     */
    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        return HCLog::error('FORGOT-PASS-002', trans($response), 200);
    }

}
