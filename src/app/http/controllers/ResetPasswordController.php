<?php

namespace interactivesolutions\honeycombacl\app\http\controllers;

use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use interactivesolutions\honeycombcore\http\controllers\HCBaseController;

class ResetPasswordController extends HCBaseController
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/';

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
     * Display the password reset view for the given token.
     *
     * If no token is present, display the link request form.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  string|null $token
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showResetForm(Request $request, $token = null)
    {
        return view('HCACL::password.reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }


    /**
     * Get the response for a successful password reset.
     *
     * @param  string $response
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendResetResponse($response)
    {
        //redirect to intended url
        return response([
            'success'     => true,
            'message'     => trans($response),
            'redirectURL' => session('url.intended', url('/')),
        ]);
    }

    /**
     * Get the response for a failed password reset.
     *
     * @param  \Illuminate\Http\Request
     * @param  string $response
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendResetFailedResponse(Request $request, $response)
    {
        return \HCLog::error('PASSWORD-RESET-2', trans($response), 200);
    }
}
