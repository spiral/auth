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
use Zend\Diactoros\Response\RedirectResponse;

class RedirectFirewall extends AbstractFirewall
{
    const REDIRECT_HTTP_STATUS = 301;

    /** @var UriInterface */
    private $redirect = null;

    /**
     * @param string|UriInterface $redirect
     */
    public function __construct($redirect)
    {
        if (!$redirect instanceof UriInterface) {
            $redirect = new Uri($redirect);
        }
        $this->setRedirect($redirect);
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
        return new RedirectResponse($this->redirect, self::REDIRECT_HTTP_STATUS);
    }
}