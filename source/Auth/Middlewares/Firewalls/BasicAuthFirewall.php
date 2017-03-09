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

class BasicAuthFirewall extends AbstractFirewall
{
    /**
     * @var string
     */
    private $realm;

    /**
     * @param string $realm
     */
    public function __construct(string $realm = 'Login')
    {
        $this->realm = $realm;
    }

    /**
     * @param string $realm
     *
     * @return BasicAuthFirewall
     */
    public function withRealm(string $realm): self
    {
        $middleware = clone $this;
        $middleware->realm = $realm;

        return $middleware;
    }

    /**
     * {@inheritdoc}
     */
    public function denyAccess(Request $request, Response $response, callable $next)
    {
        return $response->withStatus(401)->withHeader(
            'WWW-Authenticate',
            sprintf('Basic realm="%s"', $this->realm)
        );
    }
}