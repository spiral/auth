<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J), Lev Seleznev
 */
namespace Spiral\Auth\Middlewares\Firewalls;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Spiral\Auth\ContextInterface;
use Spiral\Http\MiddlewareInterface;

abstract class AbstractFirewall implements MiddlewareInterface
{
    /**
     * @param Request  $request
     * @param Response $response
     * @param callable $next
     *
     * @return mixed
     */
    public function __invoke(Request $request, Response $response, callable $next)
    {
        /**
         * @var ContextInterface $authentication
         */
        $authContext = $request->getAttribute('auth');

        if (empty($authContext) || !$authContext->isAuthenticated()) {
            return $this->denyAccess($request, $response, $next);
        }

        return $this->grantAccess($request, $response, $next);
    }

    /**
     * @param Request  $request
     * @param Response $response
     * @param callable $next
     * @return ResponseInterface
     */
    abstract public function denyAccess(Request $request, Response $response, callable $next);

    /**
     * @param Request  $request
     * @param Response $response
     * @param callable $next
     * @return ResponseInterface
     */
    public function grantAccess(Request $request, Response $response, callable $next)
    {
        return $next($request, $response);
    }
}