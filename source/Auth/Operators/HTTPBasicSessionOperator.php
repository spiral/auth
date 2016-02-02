<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J), Lev Seleznev
 */
namespace Spiral\Auth\Operators;

use Psr\Http\Message\ServerRequestInterface as Request;
use Spiral\Auth\Authenticators\CredentialsAuthenticator;
use Spiral\Auth\Operators\Session\SessionToken;
use Spiral\Auth\UserInterface;
use Spiral\Session\SessionInterface;

class HTTPBasicSessionOperator extends SessionTokenOperator
{
    /** @var CredentialsAuthenticator */
    private $authenticator;

    /**
     * @param SessionInterface $session
     * @param string $key
     * @param CredentialsAuthenticator $authenticator
     */
    public function __construct(SessionInterface $session, $key, CredentialsAuthenticator $authenticator)
    {
        $this->authenticator = $authenticator;

        parent::__construct($session, $key);
    }

    /**
     * {@inheritdoc}
     */
    public function createToken(UserInterface $user)
    {
        return new SessionToken($user->primaryKey());
    }

    /**
     * {@inheritdoc}
     */
    public function hasToken(Request $request)
    {
        $data = $request->getServerParams();

        return isset($data['PHP_AUTH_USER']);
    }

    /**
     * {@inheritdoc}
     */
    public function fetchToken(Request $request)
    {
        $data = $request->getServerParams();
        $user = $this->authenticator->getUser($data['PHP_AUTH_USER'], $data['PHP_AUTH_PW']);
        if (empty($user)) {
            return null;
        }

        return $this->createToken($user);
    }
}