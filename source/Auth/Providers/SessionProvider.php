<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */
namespace Spiral\Auth\Providers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Spiral\Auth\Entities\Token;
use Spiral\Auth\ProviderInterface;
use Spiral\Auth\TokenInterface;
use Spiral\Auth\UserInterface;
use Spiral\Session\SessionInterface;

class SessionProvider implements ProviderInterface
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
    public function hasToken(Request $request)
    {
        return $this->session->has($this->key);
    }

    /**
     * {@inheritdoc}
     */
    public function fetchToken(Request $request, $name)
    {
        return new Token($this->session->has($this->key), $name);
    }

    /**
     * {@inheritdoc}
     */
    public function createToken(Request $request, Response $response, UserInterface $user)
    {
        $this->session->set($this->key, $user->primaryKey());

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function removeToken(Request $request, Response $response, TokenInterface $token)
    {
        $this->session->delete($this->key);

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function refreshToken(Request $request, Response $response, TokenInterface $token)
    {
        return $response;
    }
}