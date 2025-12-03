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
class DummyAuthenticator extends SessionAuthenticator
{
    // Hardcoded username for authentication
    public const EMAIL = "admin@example.com";
    public const MENO = "Admin";
    public const PRIEZVISKO = "User";
    // Hash of the password "admin"
    public const PASSWORD_HASH = '$2y$10$GRA8D27bvZZw8b85CAwRee9NH5nj4CQA6PDFMc90pN9Wi4VAWq3yq';

    public const ID = 1;
    // Application instance

    public function __construct(App $app)
    {
        parent::__construct($app);
    }

    protected function authenticate(string $email, string $password): ?IIdentity
    {
        if ($email === self::EMAIL && password_verify($password, self::PASSWORD_HASH)) {
            return new Account(self::ID, self::EMAIL, self::MENO, self::PRIEZVISKO);
        }
        return null;
    }
}
