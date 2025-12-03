<?php

namespace App\Controllers;

use App\Configuration;
use App\Models\Account;
use Exception;
use Framework\Core\BaseController;
use Framework\Http\Request;
use Framework\Http\Responses\Response;
use Framework\Http\Responses\ViewResponse;

/**
 * Class AuthController
 *
 * This controller handles authentication actions such as login, logout, and redirection to the login page. It manages
 * user sessions and interactions with the authentication system.
 *
 * @package App\Controllers
 */
class AuthController extends BaseController
{
    /**
     * Redirects to the login page.
     *
     * This action serves as the default landing point for the authentication section of the application, directing
     * users to the login URL specified in the configuration.
     *
     * @return Response The response object for the redirection to the login page.
     */
    public function index(Request $request): Response
    {
        return $this->redirect(Configuration::LOGIN_URL);
    }

    /**
     * Authenticates a user and processes the login request.
     *
     * This action handles user login attempts. If the login form is submitted, it attempts to authenticate the user
     * with the provided credentials. Upon successful login, the user is redirected to the admin dashboard.
     * If authentication fails, an error message is displayed on the login page.
     *
     * @return Response The response object which can either redirect on success or render the login view with
     *                  an error message on failure.
     * @throws Exception If the parameter for the URL generator is invalid throws an exception.
     */
    public function login(Request $request): Response
    {
        $logged = null;
        if ($request->hasValue('submit')) {
            $logged = $this->app->getAuthenticator()->login($request->value('email'), $request->value('password'));
            if ($logged) {
                return $this->redirect($this->url("admin.index"));
            }
        }

        $message = $logged === false ? 'Bad email or password' : null;
        return $this->html(compact("message"));
    }

    /**
     * Logs out the current user.
     *
     * This action terminates the user's session and redirects them to a view. It effectively clears any authentication
     * tokens or session data associated with the user.
     *
     * @return ViewResponse The response object that renders the logout view.
     * @throws Exception
     */
    public function logout(Request $request): Response
    {
        $this->app->getAuthenticator()->logout();
        return $this->redirect($this->url("home.index"));
    }

    /**
     * Logs out the current user.
     *
     * This action terminates the user's session and redirects them to a view. It effectively clears any authentication
     * tokens or session data associated with the user.
     *
     * @return ViewResponse The response object that renders the logout view.
     * @throws Exception
     */

    public function register(Request $request): Response
    {
        $message = null;
        if ($request->hasValue('register')) {
            $email = trim($request->value('email'));
            $first_name = trim($request->value('first_name'));
            $last_name = trim($request->value('last_name'));
            $password = $request->value('password');
            $password2 = $request->value('password2');

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $message = "Invalid email format";
                return $this->html(compact("message"));
            }
            if (strlen($password) < 6) {
                $message = "Password must have at least 6 characters";
                return $this->html(compact("message"));
            }
            if ($password !== $password2) {
                $message = "Passwords do not match";
                return $this->html(compact("message"));
            }

            $existingUsers = Account::getCount('`email` = ?', [$email]);
            if ($existingUsers > 0) {
                $message = "User with this email already exists";
                return $this->html(compact("message"));
            }
            $hash = password_hash($password, PASSWORD_DEFAULT);

            $userModel = new Account();
            $userModel->setEmail($email);
            $userModel->setPassword($hash);
            $userModel->setFirstName($first_name);
            $userModel->setLastName($last_name);

            $userModel->save();
            return $this->redirect($this->url("auth.login"));
        }
        return $this->html(compact("message"));
    }
}
