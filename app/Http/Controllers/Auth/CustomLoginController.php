<?php

namespace App\Http\Controllers\Auth;

use AMovil\Auth\AccessControl\Domain\AuthService;
use Backpack\CRUD\app\Library\Auth\AuthenticatesUsers;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class CustomLoginController extends Controller
{
    protected $data = []; // the information we send to the view

    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */
    use AuthenticatesUsers {
        logout as defaultLogout;
    }

    private $authService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
        $guard = backpack_guard_name();

        $this->middleware("guest:$guard", ['except' => 'logout']);

        // ----------------------------------
        // Use the admin prefix in all routes
        // ----------------------------------

        // If not logged in redirect here.
        $this->loginPath = property_exists($this, 'loginPath') ? $this->loginPath
            : backpack_url('login');

        // Redirect here after successful login.
        $this->redirectTo = property_exists($this, 'redirectTo') ? $this->redirectTo
            : backpack_url('dashboard');

        // Redirect here after logout.
        $this->redirectAfterLogout = property_exists($this, 'redirectAfterLogout') ? $this->redirectAfterLogout
            : backpack_url('login');
    }

    /**
     * Return custom username for authentication.
     *
     * @return string
     */
    public function username()
    {
        return backpack_authentication_column();
    }

    /**
     * The user has logged out of the application.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    protected function loggedOut(Request $request)
    {
        return redirect($this->redirectAfterLogout);
    }

    /**
     * Get the guard to be used during logout.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return backpack_auth();
    }

    protected function attemptLogin(Request $request)
    {
        $credentials = $this->credentials($request);
        $username = $credentials[$this->username()];
        $user = \App\User::query()->where($this->username(), $username)->first();

        if($user === null){
            return false;
        }

        $auth_method_id = (string) $user->auth_method_id;

        switch ($auth_method_id) {
            case '1':
                return $this->guard()->attempt(
                    $this->credentials($request), $request->filled('remember')
                );
                break;
            case '2':
                $this->authService->login(
                    $username,
                    $credentials["password"]
                );
                if($this->authService->getUserIdentifier() !== null){
                    $this->guard()->login($user);
                    return true;
                }
                return false;
                break;
            default:
                throw new Exception("Metodo de autenticacion no valido");
                break;
        }
    }
}
