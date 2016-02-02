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
use Psr\Http\Message\UriInterface;
use Spiral\Http\Uri;

class RedirectFirewall extends AbstractFirewall
{
    /**
     * @var int
     */
    protected $status = 301;

    /**
     * @var UriInterface
     */
    protected $redirect = null;

    /**
     * @param string|UriInterface $redirect
     * @param int                 $status
     */
    public function __construct($redirect, $status = 301)
    {
        if (!$redirect instanceof UriInterface) {
            $redirect = new Uri($redirect);
        }

        $this->status = $status;

        $this->withRedirect($redirect);
    }

    /**
     * @param UriInterface $uri
     * @return RedirectFirewall
     */
    public function withRedirect(UriInterface $uri)
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