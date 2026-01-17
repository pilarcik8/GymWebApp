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
        if ($this->user->isLoggedIn()) {
            return $this->redirect($this->url("home.index"));
        }

        $email = '';
        $message = null;

        if ($request->hasValue('submit')) {
            $email = trim((string)$request->value('email'));
            $password = (string)$request->value('password');

            // Server-side validácia vstupov
            if ($email === '' || $password === '') {
                $message = 'Email aj heslo sú povinné.';
                return $this->html(compact('message', 'email'));
            }

            if (mb_strlen($email) > 255) {
                $message = 'Email je príliš dlhý (max 255 znakov).';
                return $this->html(compact('message', 'email'));
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $message = 'Zadajte platný email.';
                return $this->html(compact('message', 'email'));
            }

            if (mb_strlen($password) < 6) {
                $message = 'Heslo musí mať minimálne 6 znakov.';
                return $this->html(compact('message', 'email'));
            }

            if (mb_strlen($password) > 255) {
                $message = 'Heslo je príliš dlhé.';
                return $this->html(compact('message', 'email'));
            }

            $logged = $this->app->getAuthenticator()->login($email, $password);
            if ($logged) {
                $role = $this->app->getAuthenticator()->getUser()->getRole();
                if ($role === 'admin') {
                    return $this->redirect($this->url("admin.index"));
                } elseif ($role === 'customer') {
                    return $this->redirect($this->url("customer.index"));
                } elseif ($role === 'trainer') {
                    return $this->redirect($this->url("coach.index"));
                } elseif ($role === 'reception') {
                    return $this->redirect($this->url("reception.index"));
                }
            }

            // ak autentifikácia zlyhala
            $message = 'Nesprávny email alebo heslo';
        }

        return $this->html(compact('message', 'email'));
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
        if ($this->user->isLoggedIn()) {
            return $this->redirect($this->url("home.index"));
        }

        $message = null;
        $email = '';
        $first_name = '';
        $last_name = '';

        if ($request->hasValue('register')) {
            $email = trim((string)$request->value('email'));
            $first_name = trim((string)$request->value('first_name'));
            $last_name = trim((string)$request->value('last_name'));
            $password = (string)$request->value('password');
            $password2 = (string)$request->value('password2');

            // Povinné polia
            if ($email === '' || $first_name === '' || $last_name === '' || $password === '' || $password2 === '') {
                $message = 'Všetky polia sú povinné.';
                return $this->html(compact('message', 'email', 'first_name', 'last_name'));
            }

            // Email formát a dĺžka
            if (mb_strlen($email) > 255) {
                $message = 'Email je príliš dlhý (max 255 znakov).';
                return $this->html(compact('message', 'email', 'first_name', 'last_name'));
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $message = 'Zadajte platný email.';
                return $this->html(compact('message', 'email', 'first_name', 'last_name'));
            }

            // Mená – rozumné dĺžky
            if (mb_strlen($first_name) > 100 || mb_strlen($last_name) > 100) {
                $message = 'Meno a priezvisko môžu mať maximálne 100 znakov.';
                return $this->html(compact('message', 'email', 'first_name', 'last_name'));
            }

            // Heslo – min/max dĺžka a zhoda
            if (mb_strlen($password) < 6) {
                $message = "Heslo musí mať minimálne 6 znakov";
                return $this->html(compact("message", "email", "first_name", "last_name"));
            }
            if (mb_strlen($password) > 255) {
                $message = 'Heslo je príliš dlhé.';
                return $this->html(compact('message', 'email', 'first_name', 'last_name'));
            }
            if ($password !== $password2) {
                $message = "Heslá sa nezhodujú";
                return $this->html(compact("message", "email", "first_name", "last_name"));
            }

            // Unikátnosť emailu
            $existingUsers = Account::getCount('`email` = ?', [$email]);
            if ($existingUsers > 0) {
                $message = "Daný email je už zaregistrovaný";
                return $this->html(compact("message", "email", "first_name", "last_name"));
            }

            $hash = password_hash($password, PASSWORD_DEFAULT);
            $userModel = new Account($email, $hash, $first_name, $last_name);
            $userModel->save();

            return $this->redirect($this->url("auth.login"));
        }

        // Načítaj všetky existujúce emaily na klientsku validáciu
        $allAccounts = Account::getAll();
        $existingEmails = array_map(static function (Account $acc) {
            return $acc->getEmail();
        }, $allAccounts);

        return $this->html(compact("message", "email", "first_name", "last_name", "existingEmails"));
    }
}
