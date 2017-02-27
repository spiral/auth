<?php
/**
 * Spiral Framework, SpiralScout LLC.
 *
 * @package   spiralFramework
 * @author    Anton Titov (Wolfy-J)
 * @copyright ©2009-2011
 */

namespace Spiral\Auth;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Manages token persistence withing given request and response.
 */
interface TokenOperatorInterface
{
    /**
     * Create token for a given user intance, returned token must be associated with operator via
     * getOperator() method.
     *
     * @param UserInterface $user
     * @return TokenInterface
     */
    public function createToken(UserInterface $user): TokenInterface;

    /**
     * Check if request contains token associated with this operator.
     *
     * @param Request $request
     * @return bool
     */
    public function hasToken(Request $request): bool;

    /**
     * Fetch token from request, make sure to call hasToken first. Return null when token not found
     * or invalid.
     *
     * @param Request $request
     * @return TokenInterface|null
     */
    public function fetchToken(Request $request);

    /**
     * Must declare token in outgoing response.
     *
     * @param Request        $request
     * @param Response       $response
     * @param TokenInterface $token
     * @return Response
     */
    public function mountToken(
        Request $request,
        Response $response,
        TokenInterface $token
    ): Response;

    /**
     * Remove token presense in response and detach token from internal storage if any. On practice \
     * this method is response for de-authorization of user.
     *
     * @param Request        $request
     * @param Response       $response
     * @param TokenInterface $token
     * @return Response
     */
    public function removeToken(
        Request $request,
        Response $response,
        TokenInterface $token
    ): Response;

    /**
     * Update token presence in response, might regenerate token hash based on internal implementation.
     *
     * @param Request        $request
     * @param Response       $response
     * @param TokenInterface $token
     * @return Response
     */
    public function updateToken(
        Request $request,
        Response $response,
        TokenInterface $token
    ): Response;
}