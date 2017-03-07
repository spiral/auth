<?php
/**
 * Spiral Framework, SpiralScout LLC.
 *
 * @package   spiralFramework
 * @author    Anton Titov (Wolfy-J)
 * @copyright ©2009-2011
 */

namespace Spiral\Auth\Operators\Bridges;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Aith token Read-only from user headers.
 */
class HeaderBridge implements BridgeInterface
{
    /**
     * @var string
     */
    private $header;

    /**
     * @param string $header
     */
    public function __construct(string $header)
    {
        $this->header = $header;
    }

    /**
     * {@inheritdoc}
     */
    public function hasToken(Request $request): bool
    {
        return $request->hasHeader($this->header);
    }

    /**
     * {@inheritdoc}
     */
    public function fetchToken(Request $request)
    {
        return $request->getHeaderLine($this->header);
    }

    /**
     * {@inheritdoc}
     */
    public function writeToken(
        Request $request,
        Response $response,
        int $lifetime,
        string $token = null
    ): Response {
        if (empty($token)) {
            return $response;
        }

        return $response->withAddedHeader($this->header, $token);
    }
}