<?php
namespace Framework\Auth;

use Exception;
use Framework\Core\App;
use Framework\Core\IAuthenticator;
use Framework\Core\IIdentity;
use Framework\Http\Session;
use App\Models\Account;

/**
 * Class DummyAuthenticator
 * A basic implementation of user authentication using hardcoded email credentials.
 *
 * @package App\Auth
 * @property-read Account|null $user Associated authenticated account object (or null if not logged in).
 */
class DummyAuthenticator implements IAuthenticator
{
    // Hardcoded email for authentication
    public const EMAIL = "admin@example.com";
    // Hash of the password "admin"
    public const PASSWORD_HASH = '$2y$10$GRA8D27bvZZw8b85CAwRee9NH5nj4CQA6PDFMc90pN9Wi4VAWq3yq';
    // Application instance
    private App $app;
    // Session management instance
    private Session $session;
    // Cached authenticated account instance (nullable when not logged in)
    private ?IIdentity $user = null;

    public function __construct(App $app)
    {
        $this->app = $app;
        $this->session = $this->app->getSession();
    }

    /**
     * Authenticates a user based on hardcoded email and password.
     *
     * @param string $email User's email attempt.
     * @param string $password User's password attempt.
     * @return bool Returns true if authentication is successful; false otherwise.
     */
    public function login(string $email, string $password): bool
    {
        if ($email === self::EMAIL && password_verify($password, self::PASSWORD_HASH)) {
            $this->user = new Account(
                id: null,
                role: 'admin',
                first_name: 'Admin',
                last_name: '',
                email: self::EMAIL,
                password: '',
                credit: 0.0
            );
            // Store the Account object in session under both keys for compatibility
            $this->session->set('account', $this->user);
            $this->session->set('user', $this->user);
            return true;
        }
        return false;
    }

    /**
     * Logs out the user by destroying the session.
     */
    public function logout(): void
    {
        $this->user = null;
        $this->session->destroy();
    }

    /**
     * Checks if the user is currently authenticated.
     */
    public function isLogged(): bool
    {
        return $this->getUser() instanceof IIdentity;
    }

    /**
     * Returns the associated authenticated account object, if available.
     *
     * @return IIdentity|null
     * @throws Exception
     */
    public function getUser(): ?IIdentity
    {
        if ($this->user instanceof IIdentity) {
            return $this->user;
        }

        // Check both 'account' and legacy 'user' session keys
        $sessionValue = $this->session->get('account');
        if ($sessionValue === null) {
            $sessionValue = $this->session->get('user');
        }

        // Upgrade legacy string session value to an Account object (email stored)
        if (is_string($sessionValue) && $sessionValue !== '') {
            $this->user = new Account(
                id: null,
                role: 'user',
                first_name: '',
                last_name: '',
                email: $sessionValue,
                password: '',
                credit: 0.0
            );
            $this->session->set('account', $this->user);
            $this->session->set('user', $this->user);
            return $this->user;
        }

        if ($sessionValue instanceof Account || $sessionValue instanceof IIdentity) {
            $this->user = $sessionValue;
            return $this->user;
        }

        return null;
    }

    /**
     * Magic getter to support property-style access `$auth->user` or `$auth->account`.
     *
     * @param string $name
     * @return mixed
     * @throws Exception
     */
    public function __get(string $name): mixed
    {
        if ($name === 'user' || $name === 'account') {
            return $this->getUser();
        }
        return null;
    }
}
