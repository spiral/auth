<?php
/**
 * Spiral Framework, SpiralScout LLC.
 *
 * @package   spiralFramework
 * @author    Anton Titov (Wolfy-J)
 * @copyright ©2009-2011
 */

namespace Spiral\Auth\Operators\Bridges;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Spiral\Http\Configs\HttpConfig;
use Spiral\Http\Cookies\Cookie;

/**
 * Stores auth tokens in user cookies.
 */
class CookieBridge implements BridgeInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var HttpConfig
     */
    private $httpConfig;

    /**
     * @param string     $name
     * @param HttpConfig $config
     */
    public function __construct(string $name, HttpConfig $config)
    {
        $this->name = $name;
        $this->httpConfig = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function hasToken(Request $request): bool
    {
        $cookies = $request->getCookieParams();

        return isset($cookies[$this->name]);
    }

    /**
     * {@inheritdoc}
     */
    public function fetchToken(Request $request)
    {
        $cookies = $request->getCookieParams();

        return isset($cookies[$this->name]) ? $cookies[$this->name] : null;
    }

    /**
     * {@inheritdoc}
     */
    public function writeToken(
        Request $request,
        Response $response,
        int $lifetime,
        string $token = null
    ): Response {
        $cookie = Cookie::create(
            $this->name,
            $token,
            $lifetime,
            $this->httpConfig->basePath(),
            $this->httpConfig->cookiesDomain($request->getUri())
        );

        return $response->withAddedHeader('Set-Cookie', $cookie->createHeader());
    }
}