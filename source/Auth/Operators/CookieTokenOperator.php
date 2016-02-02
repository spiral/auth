<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J), Lev Seleznev
 */
namespace Spiral\Auth\Operators;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Spiral\Auth\TokenInterface;
use Spiral\Core\FactoryInterface;
use Spiral\Http\Configs\HttpConfig;
use Spiral\Http\Cookies\Cookie;

class CookieTokenOperator extends AbstractTokenOperator
{
    /**
     * Required to resolve valid cookie domain and path.
     *
     * @var HttpConfig
     */
    private $httpConfig;

    /**
     * @var string
     */
    private $cookie;

    /**
     * @param FactoryInterface $factory
     * @param int              $lifetime
     * @param string           $sourceClass
     * @param HttpConfig       $httpConfig
     * @param string           $cookie
     */
    public function __construct(
        FactoryInterface $factory,
        $lifetime,
        $sourceClass,
        HttpConfig $httpConfig,
        $cookie
    ) {
        parent::__construct($factory, $lifetime, $sourceClass);

        $this->httpConfig = $httpConfig;
        $this->cookie = $cookie;
    }

    /**
     * {@inheritdoc}
     */
    public function mountToken(Request $request, Response $response, TokenInterface $token)
    {
        return $response->withAddedHeader(
            'Set-Cookie',
            Cookie::create($this->cookie, $token->getHash())
        );
    }

    /**
     * {@inheritdoc}
     */
    public function removeToken(Request $request, Response $response, TokenInterface $token)
    {
        return $response->withAddedHeader(
            'Set-Cookie',
            $this->createCookie($request, null)
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function extractHash(Request $request)
    {
        $cookies = $request->getCookieParams();

        return isset($cookies[$this->cookie]) ? $cookies[$this->cookie] : null;
    }

    /**
     * @param Request     $request
     * @param string|null $hash
     * @return Cookie
     */
    protected function createCookie(Request $request, $hash)
    {
        return Cookie::create(
            $this->cookie,
            $hash,
            $this->getLifetime(),
            $this->httpConfig->basePath(),
            $this->httpConfig->cookiesDomain($request->getUri())
        );
    }
}