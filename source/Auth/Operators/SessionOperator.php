<?php
/**
 * Spiral Framework, SpiralScout LLC.
 *
 * @package   spiralFramework
 * @author    Anton Titov (Wolfy-J)
 * @copyright ©2009-2011
 */

namespace Spiral\Auth\Operators;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Spiral\Auth\AuthToken;
use Spiral\Auth\Exceptions\AuthException;
use Spiral\Auth\TokenInterface;
use Spiral\Auth\TokenOperatorInterface;
use Spiral\Auth\UserInterface;
use Spiral\Session\Http\SessionStarter;
use Spiral\Session\SectionInterface;
use Spiral\Session\SessionInterface;

/**
 * Session token operator relies on session to carry information about authenticated user. Session
 * token value is always static.
 *
 * AuthMiddleware must locate after SessionStarter middleware in order to correctly resolve session.
 */
class SessionOperator implements TokenOperatorInterface
{
    /**
     * Segment variable to store authorized user id.
     */
    const USER_PK = 'userPK';

    /**
     * Session segment to store authorization information.
     *
     * @var string
     */
    private $segment;

    /**
     * @param string $segment
     */
    public function __construct(string $segment)
    {
        $this->segment = $segment;
    }

    /**
     * {@inheritdoc}
     */
    public function createToken(UserInterface $user): TokenInterface
    {
        //Session tokens does not share session id but rather static token id
        return new AuthToken('session-token', $user->primaryKey(), $this);
    }

    /**
     * {@inheritdoc}
     */
    public function hasToken(Request $request): bool
    {
        return $this->sessionSection($request)->has(static::USER_PK);
    }

    /**
     * {@inheritdoc}
     */
    public function fetchToken(Request $request)
    {
        if (!$this->hasToken($request)) {
            return null;
        }

        return new AuthToken(
            'session-token',
            $this->sessionSection($request)->get(static::USER_PK),
            $this
        );
    }

    /**
     * {@inheritdoc}
     */
    public function mountToken(
        Request $request,
        Response $response,
        TokenInterface $token
    ): Response {
        $this->sessionSection($request)->set(
            static::USER_PK,
            $token->getUserPK()
        );

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
        $this->sessionSection($request)->clear();

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
     * Get session section from given request.
     *
     * @param Request $request
     * @return SectionInterface
     * @throws AuthException When no session is started.
     */
    protected function sessionSection(Request $request): SectionInterface
    {
        if (empty($request->getAttribute(SessionStarter::ATTRIBUTE))) {
            throw new AuthException("Unable to use authorization thought session, no session exists");
        }

        /** @var SessionInterface $session */
        $session = $request->getAttribute(SessionStarter::ATTRIBUTE);

        return $session->getSection($this->segment);
    }
}