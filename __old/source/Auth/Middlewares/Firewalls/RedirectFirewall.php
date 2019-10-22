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
use Psr\Http\Message\UriInterface;
use Spiral\Http\Uri;

class RedirectFirewall extends AbstractFirewall
{
    /**
     * @var int
     */
    private $status = 301;

    /**
     * @var UriInterface
     */
    private $redirect = null;

    /**
     * @param string|UriInterface $redirect
     * @param int                 $status
     */
    public function __construct($redirect, int $status = 301)
    {
        $this->status = $status;

        if (!$redirect instanceof UriInterface) {
            $redirect = new Uri($redirect);
        }

        $this->redirect = $redirect;
    }

    /**
     * @param UriInterface $uri
     *
     * @return RedirectFirewall
     */
    public function withRedirect(UriInterface $uri): self
    {
        $middleware = clone $this;
        $middleware->redirect = $uri;

        return $middleware;
    }

    /**
     * {@inheritdoc}
     */
    public function denyAccess(Request $request, Response $response, callable $next)
    {
        return $response->withStatus($this->status)->withHeader(
            'Location',
            (string)$this->redirect
        );
    }
}