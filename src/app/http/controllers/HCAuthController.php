<?php

namespace interactivesolutions\honeycombacl\app\http\controllers;

use DB;
use HCLog;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use interactivesolutions\honeycombcore\http\controllers\HCBaseController;
use Illuminate\Http\Request;

class HCAuthController extends HCBaseController
{
    use AuthenticatesUsers;

    /**
     * Max login attempts
     *
     * @var int
     */
    protected $maxLoginAttempts = 5;

    /**
     * The number of minutes to delay further login attempts.
     *
     * @var int
     */
    protected $lockoutTime = 1;

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
    public function showLoginForm()
    {
        $config = [];

        return view('HCACL::auth.login', $config);
    }

    /**
     * Function which login users
     *
     * @param Request $request
     * @return string
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if( $this->hasTooManyLoginAttempts($request) ) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        if( ! $this->attemptLogin($request) ) {
            return HCLog::info('AUTH-002', trans('HCACL::users.errors.login'));
        }

        $this->sendLoginResponse($request);

        // check if user is not activated
        if( auth()->user()->isNotActivated() ) {
            $user = auth()->user();

            $this->logout($request);

            $response = $this->activation->sendActivationMail($user);

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

            if( get_class($response) == 'Illuminate\Http\JsonResponse' )
                return $response;

        } catch ( \Exception $e ) {
            DB::rollback();

            return response(['success' => false, 'message' => 'AUTH-004 - ' . $e->getMessage()]);
        }

        DB::commit();

        session(['activation_message' => trans('HCACL::users.activation.activate_account')]);

        if( $this->redirectUrl ) {
            return response(['success' => true, 'redirectURL' => $this->redirectUrl]);
        } else {
            return response(['success' => true, 'redirectURL' => route('auth.login')]);
        }
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
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->flush();

        $request->session()->regenerate();

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
     * Show activation page
     *
     * @param $token
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showActivation(string $token)
    {
        $message = null;

        $tokenRecord = DB::table('hc_users_activations')->where('token', $token)->first();

        if( is_null($tokenRecord) ) {
            $message = trans('HCACL::users.activation.token_not_exists');
        } else {
            if( strtotime($tokenRecord->created_at) + 60 * 60 * 24 < time() ) {
                $message = trans('HCACL::users.activation.token_expired');
            }
        }

        return view('HCACL::auth.activation', ['token' => $token, 'message' => $message]);
    }

    /**
     * Active user account
     *
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function activate()
    {
        DB::beginTransaction();

        try {
            $this->activation->activateUser(
                request()->input('token')
            );
        } catch ( \Exception $e ) {
            DB::rollback();

            return redirect()->back()->withErrors($e->getMessage());
        }

        DB::commit();

        return redirect()->intended();
    }

    /**
     * Determine if the user has too many failed login attempts.
     *
     * @param Request|Request $request
     * @return bool
     */
    protected function hasTooManyLoginAttempts(Request $request)
    {
        return $this->limiter()->tooManyAttempts(
            $this->throttleKey($request), $this->maxLoginAttempts, $this->lockoutTime
        );
    }


    /**
     * Redirect the user after determining they are locked out.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendLockoutResponse(Request $request)
    {
        $seconds = $this->limiter()->availableIn(
            $this->throttleKey($request)
        );

        return HCLog::info('AUTH-005', trans('auth.throttle', ['seconds' => $seconds]));
    }
}
