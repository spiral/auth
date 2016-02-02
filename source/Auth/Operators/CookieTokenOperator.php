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
use Psr\Http\Message\UriInterface;
use Spiral\Auth\Configs\AuthConfig;
use Spiral\Auth\Sources\TokenSourceInterface;
use Spiral\Auth\TokenInterface;
use Spiral\Auth\TokenOperatorInterface;
use Spiral\Auth\UserInterface;
use Spiral\Core\FactoryInterface;
use Spiral\Http\Cookies\Cookie;

class CookieTokenOperator implements TokenOperatorInterface
{
    /** @var AuthConfig */
    protected $config;

    /** @var TokenSourceInterface */
    protected $source;

    /** @var FactoryInterface */
    protected $factory;

    /** @var string */
    protected $name;

    /** @var int */
    protected $lifetime;

    public function __construct(FactoryInterface $factory, $name, $lifetime, $source)
    {
        $this->factory = $factory;
        $this->name = $name;
        $this->lifetime = intval($lifetime);
        $this->source = $factory->make($source);
    }

    /**
     * @return TokenSourceInterface
     */
    public function getSource()
    {
        if (!is_object($this->source)) {
            $this->source = $this->factory->make($this->source);
        }

        return $this->source;
    }

    /**
     * @param Request $request
     * @return string
     */
    protected function extractHash(Request $request)
    {
        $data = $request->getCookieParams();

        return isset($data[$this->name]) ? $data[$this->name] : null;
    }

    /**
     * {@inheritdoc}
     */
    public function createToken(UserInterface $user)
    {
        return $this->getSource()->createToken($user);
    }

    /**
     * {@inheritdoc}
     */
    public function hasToken(Request $request)
    {
        $hash = $this->extractHash($request);
        if (!empty($hash)) {
            $hash = $this->getSource()->hasToken($hash);
        }

        return !empty($hash);
    }

    /**
     * {@inheritdoc}
     */
    public function fetchToken(Request $request)
    {
        $hash = $this->extractHash($request);
        if (!empty($hash)) {
            $hash = $this->getSource()->getToken($hash);
        }

        return $hash;
    }

    /**
     * {@inheritdoc}
     */
    public function mountToken(Request $request, Response $response, TokenInterface $token)
    {
        $this->getSource()->refreshToken($token);

        $cookie = $this->createCookie($request->getUri(), $token->getHash());
        $response = $response->withAddedHeader(
            'Set-Cookie',
            $cookie->createHeader()
        );

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function removeToken(Request $request, Response $response, TokenInterface $token)
    {
        $this->getSource()->refreshToken($token);

        $cookie = $this->createCookie($request->getUri(), '');
        $response = $response->withAddedHeader(
            'Set-Cookie',
            $cookie->createHeader()
        );

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function updateToken(Request $request, Response $response, TokenInterface $token)
    {
        $this->getSource()->refreshToken($token);

        $cookie = $this->createCookie($request->getUri(), $token->getHash());
        $response = $response->withAddedHeader(
            'Set-Cookie',
            $cookie->createHeader()
        );

        return $response;
    }

    /**
     * todo: check for correct creation
     *
     * @param UriInterface $uri
     * @param string $value
     * @return Cookie
     */
    private function createCookie(UriInterface $uri, $value)
    {
        return Cookie::create(
            $this->name,
            $value,
            $this->lifetime > 0 ? $this->lifetime : null,
            '/',
            $uri->getHost()
        );
    }
}