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
use Spiral\Auth\Authenticators\CredentialsAuthenticator;
use Spiral\Auth\Operators\Session\BasicToken;
use Spiral\Auth\TokenInterface;
use Spiral\Auth\TokenOperatorInterface;
use Spiral\Auth\UserInterface;

class BasicTokenOperator implements TokenOperatorInterface
{
    /**
     * @var CredentialsAuthenticator
     */
    private $authenticator;

    /**
     * @param CredentialsAuthenticator $authenticator
     */
    public function __construct(CredentialsAuthenticator $authenticator)
    {
        $this->authenticator = $authenticator;
    }

    /**
     * {@inheritdoc}
     */
    public function createToken(UserInterface $user)
    {
        return new BasicToken($user->primaryKey());
    }

    /**
     * {@inheritdoc}
     */
    public function hasToken(Request $request)
    {
        $header = $request->getHeaderLine('Authorization');

        return strpos($header, 'Basic') === 0;
    }

    /**
     * {@inheritdoc}
     */
    public function fetchToken(Request $request)
    {
        $header = $request->getHeaderLine('Authorization');

        if (strpos($header, 'Basic') !== 0) {
            return null;
        }

        list($username, $password) = $this->parseHeader($header);

        //Direct authentication
        $user = $this->authenticator->getUser($username, $password);
        if (empty($user)) {
            return null;
        }

        return $this->createToken($user);
    }

    /**
     * {@inheritdoc}
     */
    public function mountToken(Request $request, Response $response, TokenInterface $token)
    {
        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function removeToken(Request $request, Response $response, TokenInterface $token)
    {
        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function updateToken(Request $request, Response $response, TokenInterface $token)
    {
        return $response;
    }

    /**
     * @param string $header
     * @return array
     */
    private static function parseHeader($header)
    {
        $header = explode(':', base64_decode(substr($header, 6)), 2);

        return [$header[0], isset($header[1]) ? $header[1] : null];
    }
}