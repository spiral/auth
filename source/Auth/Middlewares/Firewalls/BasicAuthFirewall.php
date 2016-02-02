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

class BasicAuthFirewall extends AbstractFirewall
{
    const REDIRECT_HTTP_STATUS = 301;

    /** @var string */
    protected $realm;

    /**
     * @param string $realm
     */
    public function __construct($realm = 'Admin area')
    {
        $this->realm = $realm;
    }

    /**
     * {@inheritdoc}
     */
    public function onAccessDenied(Request $request, Response $response, callable $next)
    {
        $value = sprintf('Basic realm="%s"', $this->realm);

        return $response->withStatus('401')->withHeader('WWW-Authenticate', $value);
    }
}