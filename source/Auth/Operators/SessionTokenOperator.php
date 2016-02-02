<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J), Lev Seleznev
 */
namespace Spiral\Auth\Operators;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Spiral\Auth\Exceptions\InvalidTokenException;
use Spiral\Auth\Operators\Session\SessionToken;
use Spiral\Auth\TokenInterface;
use Spiral\Auth\TokenOperatorInterface;
use Spiral\Auth\UserInterface;
use Spiral\Session\SessionInterface;

class SessionTokenOperator implements TokenOperatorInterface
{
    /**
     * @var SessionInterface
     */
    private $session = null;

    /**
     * @var string
     */
    private $key;

    /**
     * @param SessionInterface $session
     * @param string           $key
     */
    public function __construct(SessionInterface $session, $key)
    {
        $this->session = $session;
        $this->key = $key;
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
        return $this->session->has($this->key);
    }

    /**
     * {@inheritdoc}
     */
    public function fetchToken(Request $request)
    {
        return new SessionToken($this->session->get($this->key));
    }

    /**
     * {@inheritdoc}
     */
    public function mountToken(Request $request, Response $response, TokenInterface $token)
    {
        if (!$token instanceof SessionToken) {
            throw new InvalidTokenException("Only session tokens are allowed");
        }

        $this->session->set($this->key, $token->getUserPK());

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function removeToken(Request $request, Response $response, TokenInterface $token)
    {
        if (!$token instanceof SessionToken) {
            throw new InvalidTokenException("Only session tokens are allowed");
        }

        $this->session->delete($this->key);

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function updateToken(Request $request, Response $response, TokenInterface $token)
    {
        if (!$token instanceof SessionToken) {
            throw new InvalidTokenException("Only session tokens are allowed");
        }

        return $response;
    }
}
