<?php
/**
 * Spiral Framework, SpiralScout LLC.
 *
 * @package   spiralFramework
 * @author    Anton Titov (Wolfy-J)
 * @copyright ©2009-2011
 */

namespace Spiral\Auth\Middlewares\Firewalls;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Spiral\Http\Exceptions\ClientExceptions\ForbiddenException;

class ExceptionFirewall extends AbstractFirewall
{
    /**
     * {@inheritdoc}
     */
    public function denyAccess(Request $request, Response $response, callable $next)
    {
        throw new ForbiddenException();
    }
}