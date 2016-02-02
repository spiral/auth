<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J), Lev Seleznev
 */
namespace Spiral\Auth\Middlewares\Firewalls;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Symfony\Component\Finder\Exception\AccessDeniedException;

class ExceptionFirewall extends AbstractFirewall
{
    /**
     * {@inheritdoc}
     */
    public function onAccessDenied(Request $request, Response $response, callable $next)
    {
        throw new AccessDeniedException('Access denied');
    }
}