<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J), Lev Seleznev
 */
namespace Spiral\Auth\Middlewares\Firewalls;

use Psr\Http\Message\UriInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Spiral\Http\Uri;

class RedirectFirewall extends AbstractFirewall
{
    protected $status;

    /** @var UriInterface */
    protected $redirect = null;

    /**
     * @param $redirect
     * @param int $status
     */
    public function __construct($redirect, $status = 301)
    {
        if (!$redirect instanceof UriInterface) {
            $redirect = new Uri($redirect);
        }

        $this->setRedirect($redirect);
        $this->status = $status;
    }

    /**
     * @param UriInterface $uri
     */
    public function setRedirect(UriInterface $uri)
    {
        $this->redirect = $uri;
    }

    /**
     * {@inheritdoc}
     */
    public function onAccessDenied(Request $request, Response $response, callable $next)
    {
        return $response->withStatus($this->status)->withHeader('Location',(string)$this->redirect);
    }
}