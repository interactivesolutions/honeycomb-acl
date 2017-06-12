<?php

namespace interactivesolutions\honeycombacl\app\http\controllers;

use DB;
use HCLog;
use Request;
use Validator;
use interactivesolutions\honeycombcore\http\controllers\HCBaseController;
use Illuminate\Http\Request as HCRequest;

class HCAuthController extends HCBaseController
{
    /**
     * Max login attempts
     *
     * @var int
     */
    protected $maxLoginAttempts = 5;

    /**
     * The number of seconds to delay further login attempts.
     *
     * @var int
     */
    protected $lockoutTime = 60;

    /**
     * Redirect url
     *
     * @var
     */
    protected $redirectUrl;

    /**
     * @var UserActivation
     */
    private $activation;

    /**
     * AuthController constructor.
     * @param UserActivation $activation
     */
    public function __construct(UserActivation $activation)
    {
        $this->activation = $activation;
    }


    /**
     * Displays users login form
     *
     * @return mixed
     */
    public function showLogin()
    {
        $config = [];

        return view('HCACL::auth.login', $config);
    }

    /**
     * Function which login users
     *
     * @param HCRequest $request
     * @return string
     */
    public function login(HCRequest $request)
    {
        $data = request()->only('email', 'password');

        //TODO validate form
        //TODO user throttles

        $auth = auth()->guard('web');

        if( ! $auth->attempt($data) ) {
            return response(['success' => false, 'message' => 'AUTH-002 - ' . trans('HCACL::users.errors.login')]);
        }

        // check if user is not activated
        if( auth()->user()->isNotActivated() ) {
            $response = $this->activation->sendActivationMail(auth()->user());

            $this->logout();

            return HCLog::info('AUTH-003', $response);
        }

        //TODO update providers?

        auth()->user()->updateLastLogin();

        //redirect to intended url
        return response(['success' => true, 'redirectURL' => session('url.intended', url('/'))]);
    }

    /**
     * Display users register form
     *
     * @return \Illuminate\View\View
     */
    public function showRegister()
    {

        return view('HCACL::auth.register');
        /*$settings = OCSettings::whereType ('ocv3users')->lists ('value', 'key');

        if ($settings['registration_enabled'] === 'true')

        return redirect ()->back ();*/
    }

    /**
     * User registration
     *
     * @return mixed
     */
    public function register()
    {
        $userController = new HCUsersController();

        DB::beginTransaction();

        try {
            $response = $userController->apiStore();

            if (get_class($response) == 'Illuminate\Http\JsonResponse')
                return $response;

        } catch (\Exception $e) {
            DB::rollback();

            return response(['success' => false, 'message' => 'AUTH-003 - ' . $e->getMessage()]);
        }

        DB::commit();

        session(['activation_message' => trans('HCACL::users.activation.activate_account')]);

        if( $this->redirectUrl )
            return response(['success' => true, 'redirectURL' => $this->redirectUrl]);
        else
            return response(['success' => true, 'redirectURL' => route('auth.login')]);

        /*if (settings ('registration_enabled') !== 'true')
            return redirect ()->back ();

        try {
            (new RegisterForm())->validateForm ();
        } catch (\Exception $e) {
            return OCLog::info ('AUTH-002', $e->getMessage ());
        }

        $usersController = new OCUsersController();

        $data = $this->getData ($usersController->getInputData ());

        DB::beginTransaction ();

        try {
            $response = $usersController->create ($data);

            if (get_class ($response) == 'Illuminate\Http\JsonResponse')
                return $response;

        } catch (\Exception $e) {
            DB::rollback ();

            return OCLog::info ('AUTH-003', $e->getMessage ());
        }

        DB::commit ();

        session (['activation_message' => trans ('HCACL::users.activation.activate_account')]);

        if ($this->redirectUrl)
            return response (['success' => true, 'redirectURL' => $this->redirectUrl]);
        else
            return response (['success' => true, 'redirectURL' => route ('auth.login')]);*/
    }

    /**
     * Get input data
     *
     * @param $data
     * @return mixed
     */
    protected function getData(array $data)
    {
        /* // get nickname from first part of email and add timestamp after it
         $nickname = head (explode ('@', array_get ($data, 'userData.email'))) . '_' . Carbon::now ()->timestamp;

         array_set ($data, 'userPersonalData.nickname', $nickname);

         $basicRole = ACLRole::whereSlug ('basic')->firstOrFail ();

         array_set ($data, 'roles', [$basicRole->id]);

         return $data;*/
    }

    /**
     * Logout function
     */
    public function logout()
    {
        // clear the session
        \Session::flush();

        auth()->logout();

        return redirect('/')
            ->with('flash_notice', trans('HCACL::users.success.logout'));
    }

    /**
     * Update user providers during login
     */
    protected function updateProviders()
    {
        /*$user = auth ()->user ();

        $provider = 'LOCAL';

        $user->update (['provider' => $provider]);

        $user->providers ()->sync ([$provider], false);*/
    }

    /**
     * Validator error messages with lithuanian translations messages
     *
     * @return array
     */
    protected function messages()
    {
        return [
            'nickname.required'  => trans('HCACL::validator.nickname.required'),
            'nickname.unique'    => trans('HCACL::validator.nickname.unique'),
            'nickname.min'       => trans('HCACL::validator.nickname.min', ['count' => 3]),
            'email.required'     => trans('HCACL::validator.email.required'),
            'email.unique'       => trans('HCACL::validator.email.unique'),
            'email.min'          => trans('HCACL::validator.email.min', ['count' => 5]),
            'password.required'  => trans('HCACL::validator.password.required'),
            'password.min'       => trans('HCACL::validator.password.min', ['count' => 5]),
            'password.confirmed' => trans('HCACL::validator.password.confirmed'),
            'roles.required'     => trans('HCACL::validator.roles.required'),
        ];
    }

    /**
     * Show activation page
     *
     * @param $token
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showActivation(string $token)
    {
        $message = null;

        $tokenRecord = DB::table ('hc_users_activations')->where ('token', $token)->first ();

        if (is_null ($tokenRecord)) {
            $message = trans ('HCACL::users.activation.token_not_exists');
        } else {
            if (strtotime ($tokenRecord->created_at) + 60 * 60 * 24 < time ())
                $message = trans ('HCACL::users.activation.token_expired');
        }

        return view ('HCACL::auth.activation', ['token' => $token, 'message' => $message]);
    }

    /**
     * Active user account
     *
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function activate()
    {
        DB::beginTransaction ();

        try {
            $this->activation->activateUser (
                request ()->input ('token')
            );
        } catch (\Exception $e) {
            DB::rollback ();

            return redirect ()->back ()->withErrors ($e->getMessage ());
        }

        DB::commit ();

        return redirect ()->intended ();
    }

}
