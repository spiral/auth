<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J), Lev Seleznev
 */
namespace Spiral\Auth\Middlewares\Firewalls;

use Spiral\Auth\Entities\AuthContext;
use Spiral\Http\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

abstract class AbstractFirewall implements MiddlewareInterface
{
    /**
     * @param Request $request
     * @param Response $response
     * @param callable $next
     *
     * @return mixed
     */
    public function __invoke(Request $request, Response $response, callable $next)
    {
        /** @var AuthContext $authentication */
        $authContext = $request->getAttribute('auth');

        if (empty($authContext) || !$authContext->isAuthenticated()) {
            return $this->onAccessDenied($request, $response, $next);
        }

        return $this->onAccessGranted($request, $response, $next);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param callable $next
     *
     * @return mixed
     */
    abstract public function onAccessDenied(Request $request, Response $response, callable $next);

    /**
     * @param Request $request
     * @param Response $response
     * @param callable $next
     *
     * @return mixed
     */
    public function onAccessGranted(Request $request, Response $response, callable $next)
    {
        return $next($request, $response);
    }
}