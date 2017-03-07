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
use Spiral\Auth\AuthToken;
use Spiral\Auth\Exceptions\CredentialsException;
use Spiral\Auth\TokenInterface;
use Spiral\Auth\TokenOperatorInterface;
use Spiral\Auth\UserInterface;

/**
 * Provides authorization based on HTTP based Authorization request. Performs password validation
 * on every request!
 */
class BasicAuthOperator implements TokenOperatorInterface
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
    public function createToken(UserInterface $user): TokenInterface
    {
        return new AuthToken('basic-auth', $user->primaryKey(), $this);
    }

    /**
     * {@inheritdoc}
     */
    public function hasToken(Request $request): bool
    {
        //Auth token does not exists in basic auth, but still identified by specific header
        return strpos($request->getHeaderLine('Authorization'), 'Basic') === 0;
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
        try {
            $user = $this->authenticator->getUser($username, $password);
        } catch (CredentialsException $e) {
            return null;
        }

        return new AuthToken('basic-auth', $user->primaryKey(), $this);
    }

    /**
     * {@inheritdoc}
     */
    public function mountToken(
        Request $request,
        Response $response,
        TokenInterface $token
    ): Response {
        //Nothing to do
        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function removeToken(
        Request $request,
        Response $response,
        TokenInterface $token
    ): Response {
        //Nothing to do
        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function updateToken(
        Request $request,
        Response $response,
        TokenInterface $token
    ): Response {
        //Nothing to do
        return $response;
    }

    /**
     * @param string $header
     * @return array
     */
    private static function parseHeader(string $header): array
    {
        $header = explode(':', base64_decode(substr($header, 6)), 2);

        return [$header[0], isset($header[1]) ? $header[1] : null];
    }
}