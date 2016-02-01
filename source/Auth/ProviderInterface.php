<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J), Lev Seleznev
 */
namespace Spiral\Auth;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Spiral\Auth\Exceptions\InvalidTokenException;

interface ProviderInterface
{
    /**
     * @param Request $request
     * @return bool
     */
    public function hasToken(Request $request);

    /**
     * @param Request $request
     * @return TokenInterface|null
     */
    public function fetchToken(Request $request);

    /**
     * @todo split into another abstraction
     * @param UserInterface $user
     * @return TokenInterface
     */
    public function createToken(UserInterface $user);

    /**
     * @param Request        $request
     * @param Response       $response
     * @param TokenInterface $token
     * @return Response
     * @throws InvalidTokenException
     */
    public function mountToken(Request $request, Response $response, TokenInterface $token);

    /**
     * @param Request        $request
     * @param Response       $response
     * @param TokenInterface $token
     * @return Response
     * @throws InvalidTokenException
     */
    public function removeToken(Request $request, Response $response, TokenInterface $token);

    /**
     * @param Request        $request
     * @param Response       $response
     * @param TokenInterface $token
     * @return Response
     * @throws InvalidTokenException
     */
    public function refreshToken(Request $request, Response $response, TokenInterface $token);
}