<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */
namespace Spiral\Auth;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

interface ProviderInterface
{
    /**
     * @param Request $request
     * @return bool
     */
    public function hasToken(Request $request);

    /**
     * @param Request $request
     * @param string  $name
     * @return TokenInterface|null
     */
    public function fetchToken(Request $request, $name);

    /**
     * @param Request       $request
     * @param Response      $response
     * @param UserInterface $user
     * @return Response
     */
    public function createToken(Request $request, Response $response, UserInterface $user);

    /**
     * @param Request        $request
     * @param Response       $response
     * @param TokenInterface $token
     * @return Response
     */
    public function removeToken(Request $request, Response $response, TokenInterface $token);

    /**
     * @param Request        $request
     * @param Response       $response
     * @param TokenInterface $token
     * @return Response
     */
    public function refreshToken(Request $request, Response $response, TokenInterface $token);
}