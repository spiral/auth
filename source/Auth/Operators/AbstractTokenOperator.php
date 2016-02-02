<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */
namespace Spiral\Auth\Operators;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Spiral\Auth\Exceptions\UndefinedTokenException;
use Spiral\Auth\Sources\TokenSourceInterface;
use Spiral\Auth\TokenInterface;
use Spiral\Auth\TokenOperatorInterface;
use Spiral\Auth\UserInterface;
use Spiral\Core\FactoryInterface;

abstract class AbstractTokenOperator implements TokenOperatorInterface
{
    /**
     * Persistent token storage.
     *
     * @var TokenSourceInterface
     */
    private $source = null;

    /**
     * Class needed to represent token storage.
     *
     * @var string
     */
    private $sourceClass = null;

    /**
     * @var int
     */
    private $lifetime = 0;

    /**
     * Lazy loading for token storage.
     *
     * @var FactoryInterface
     */
    protected $factory = null;

    /**
     * AbstractPersistentOperator constructor.
     *
     * @param FactoryInterface $factory
     * @param int              $lifetime
     * @param string           $sourceClass
     */
    public function __construct(FactoryInterface $factory, $lifetime, $sourceClass)
    {
        $this->factory = $factory;
        $this->lifetime = $lifetime;
        $this->sourceClass = $sourceClass;
    }

    /**
     * @return int
     */
    public function getLifetime()
    {
        return $this->lifetime;
    }

    /**
     * {@inheritdoc}
     */
    public function createToken(UserInterface $user)
    {
        return $this->tokenSource()->createToken($user, $this->lifetime);
    }

    /**
     * {@inheritdoc}
     */
    public function hasToken(Request $request)
    {
        return !empty($this->extractHash($request));
    }

    /**
     * {@inheritdoc}
     */
    public function fetchToken(Request $request)
    {
        $hash = $this->extractHash($request);

        try {
            return $this->tokenSource()->getToken($hash);
        } catch (UndefinedTokenException $e) {
            return null;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function updateToken(Request $request, Response $response, TokenInterface $token)
    {
        $this->tokenSource()->updateToken($token, $this->lifetime);

        return $response;
    }

    /**
     * Must extract token hash from request if any.
     *
     * @param Request $request
     * @return string|null
     */
    abstract protected function extractHash(Request $request);

    /**
     * @return TokenSourceInterface
     */
    protected function tokenSource()
    {
        if (empty($this->source)) {
            $this->source = $this->factory->make($this->sourceClass);
        }

        return $this->source;
    }
}