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
     * When true token lifetime will be refreshed/touched on every visit. Slows down system a bit.
     *
     * @var bool
     */
    private $updateTokens;

    /**
     * @param TokenSourceInterface $source
     * @param BridgeInterface      $bridge
     * @param int                  $lifetime
     * @param bool                 $updateTokens
     */
    public function __construct(
        TokenSourceInterface $source,
        BridgeInterface $bridge,
        int $lifetime,
        bool $updateTokens = false
    ) {
        $this->source = $source;
        $this->bridge = $bridge;
        $this->lifetime = $lifetime;
        $this->updateTokens = $updateTokens;
    }

    /**
     * {@inheritdoc}
     */
    public function createToken(UserInterface $user): TokenInterface
    {
        return $this->source->createToken($user, $this->lifetime)->withOperator($this);
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

        $token = $this->source->findToken($this->bridge->fetchToken($request));
        if (empty($token)) {
            //Token not found or expired
            return null;
        }

        return $token->withOperator($this);
    }

    /**
     * {@inheritdoc}
     */
    public function commitToken(
        Request $request,
        Response $response,
        TokenInterface $token
    ): Response {
        if ($this->updateTokens) {
            $this->source->touchToken($token, $this->lifetime);
        }

        return $this->bridge->writeToken($request, $response, $this->lifetime, null);
    }

    /**
     * {@inheritdoc}
     */
    public function removeToken(
        Request $request,
        Response $response,
        TokenInterface $token
    ): Response {
        $this->source->deleteToken($token);

        //Reset user token value
        return $this->bridge->writeToken($request, $response, $this->lifetime, null);
    }
}