<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */
declare(strict_types=1);

namespace Spiral\Auth\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Spiral\Auth\TokenInterface;

/**
 * Provides the ability to read and write token values using PSR-7 Request/Response.
 */
interface HttpTransportInterface
{
    /**
     * Fetch token from incoming request, if any.
     *
     * @param Request $request
     * @return TokenInterface|null
     */
    public function fetchToken(Request $request): ?TokenInterface;

    /**
     * Commit (write) token to the outgoing response.
     *
     * @param Request        $request
     * @param Response       $response
     * @param TokenInterface $token
     * @return Response
     */
    public function commitToken(Request $request, Response $response, TokenInterface $token): Response;

    /**
     * Remove token from the outgoing response.
     *
     * @param Request        $request
     * @param Response       $response
     * @param TokenInterface $token
     * @return Response
     */
    public function removeToken(Request $request, Response $response, TokenInterface $token): Response;
}