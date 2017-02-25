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
use Spiral\Auth\Hashes\TokenHashes;
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
    protected $httpConfig;

    /**
     * @var string
     */
    protected $cookie;

    /**
     * @param FactoryInterface $factory
     * @param int              $lifetime
     * @param string           $sourceClass
     * @param HttpConfig       $httpConfig
     * @param TokenHashes      $hashes
     * @param string           $cookie
     */
    public function __construct(
        FactoryInterface $factory,
        $lifetime,
        $sourceClass,
        HttpConfig $httpConfig,
        TokenHashes $hashes,
        $cookie
    ) {
        parent::__construct($factory, $lifetime, $sourceClass, $hashes);

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
            $this->cookieHeader($request, $token->getHash())
        );
    }

    /**
     * {@inheritdoc}
     */
    public function removeToken(Request $request, Response $response, TokenInterface $token)
    {
        return $response->withAddedHeader(
            'Set-Cookie',
            $this->cookieHeader($request, null)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function updateToken(Request $request, Response $response, TokenInterface $token)
    {
        $response = parent::updateToken($request, $response, $token);

        return $this->mountToken($request, $response, $token);
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
     * @return string
     */
    protected function cookieHeader(Request $request, $hash)
    {
        return Cookie::create(
            $this->cookie,
            $hash,
            $this->getLifetime(),
            $this->httpConfig->basePath(),
            $this->httpConfig->cookiesDomain($request->getUri())
        )->createHeader();
    }
}