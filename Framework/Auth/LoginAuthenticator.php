<?php

namespace Framework\Auth;

use App\Models\Account;
use Framework\Core\App;
use Framework\Core\IIdentity;

/**
 * Class DummyAuthenticator
 * A basic implementation of user authentication using hardcoded credentials.
 *
 * @package App\Auth
 */
class LoginAuthenticator extends SessionAuthenticator
{
    public function __construct(App $app)
    {
        parent::__construct($app);
    }

    protected function authenticate(string $email, string $password): ?IIdentity
    {
        // email je DISTINCT v tabulke accounts
        $accounts = Account::getAll('`email` = ?', [$email]);

        foreach ($accounts as $acc) {
            $hash = $acc->getPassword();

            if ($hash && password_verify($password, $hash)) {
                if ($acc instanceof IIdentity) {
                    return $acc;
                }
            }
        }

        return null;
    }
}
