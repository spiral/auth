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
 * Stores auth tokens in user cookies. Cookies options depends on HttpConfig.
 */
class CookieBridge implements BridgeInterface
{
    /**
     * @var string
     */
    private $cookie;

    /**
     * @var HttpConfig
     */
    private $httpConfig;

    /**
     * @param string     $cookie
     * @param HttpConfig $config
     */
    public function __construct(string $cookie, HttpConfig $config)
    {
        $this->cookie = $cookie;
        $this->httpConfig = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function hasToken(Request $request): bool
    {
        $cookies = $request->getCookieParams();

        return isset($cookies[$this->cookie]);
    }

    /**
     * {@inheritdoc}
     */
    public function fetchToken(Request $request)
    {
        $cookies = $request->getCookieParams();

        return isset($cookies[$this->cookie]) ? $cookies[$this->cookie] : null;
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
        $cookies = $request->getCookieParams();

        if (isset($cookies[$this->cookie]) && $cookies[$this->cookie] == $token) {
            //Nothing to do
            return $response;
        }

        $cookie = Cookie::create(
            $this->cookie,
            $token,
            $lifetime,
            $this->httpConfig->basePath(),
            $this->httpConfig->cookiesDomain($request->getUri())
        );

        return $response->withAddedHeader('Set-Cookie', $cookie->createHeader());
    }
}