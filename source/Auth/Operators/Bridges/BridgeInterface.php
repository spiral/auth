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
 * Used by PersistentOperator to manage token placement in request and response.
 */
interface BridgeInterface
{
    /**
     * Must return true if request contains auth token.
     *
     * @param Request $request
     * @return bool
     */
    public function hasToken(Request $request): bool;

    /**
     * Fetch token value from incoming request if any.
     *
     * @param Request $request
     * @return string|null
     */
    public function fetchToken(Request $request);

    /**
     * Write token value into outcoming response.
     *
     * @param Request     $request
     * @param Response    $response
     * @param int         $lifetime
     * @param string|null $token
     * @return Response
     */
    public function writeToken(
        Request $request,
        Response $response,
        int $lifetime,
        string $token = null
    ): Response;
}