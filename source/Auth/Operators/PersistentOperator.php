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
use Spiral\Auth\Operators\Bridges\BridgeInterface;
use Spiral\Auth\Sources\TokenSourceInterface;
use Spiral\Auth\TokenInterface;
use Spiral\Auth\TokenOperatorInterface;
use Spiral\Auth\UserInterface;

/**
 * Provides ability to create persistent tokens thought associated token source.
 */
class PersistentOperator implements TokenOperatorInterface
{
    /**
     * @var TokenSourceInterface
     */
    private $source;

    /**
     * @var BridgeInterface
     */
    private $bridge;

    /**
     * @var int
     */
    private $lifetime;

    /**
     * @param TokenSourceInterface $source
     * @param BridgeInterface      $bridge
     * @param int                  $lifetime
     */
    public function __construct(
        TokenSourceInterface $source,
        BridgeInterface $bridge,
        int $lifetime
    ) {
        $this->source = $source;
        $this->bridge = $bridge;
        $this->lifetime = $lifetime;
    }

    /**
     * {@inheritdoc}
     */
    public function createToken(UserInterface $user): TokenInterface
    {
        return $this->source->createToken($user, $this->lifetime);
    }

    /**
     * {@inheritdoc}
     */
    public function hasToken(Request $request): bool
    {
        return $this->bridge->hasToken($request);
    }

    /**
     * {@inheritdoc}
     */
    public function fetchToken(Request $request)
    {
        if (!$this->hasToken($request)) {
            return null;
        }

        return $this->source->getToken($this->bridge->fetchToken($request));
    }

    /**
     * {@inheritdoc}
     */
    public function mountToken(
        Request $request,
        Response $response,
        TokenInterface $token
    ): Response {
        // TODO: Implement mountToken() method.
    }

    /**
     * {@inheritdoc}
     */
    public function removeToken(
        Request $request,
        Response $response,
        TokenInterface $token
    ): Response {
        //Reset user token value
        return $this->bridge->writeToken($request, $response, null);
    }

    /**
     * {@inheritdoc}
     */
    public function updateToken(
        Request $request,
        Response $response,
        TokenInterface $token
    ): Response {

        return $this->bridge->writeToken($request, $response, $token->getValue());
    }
}