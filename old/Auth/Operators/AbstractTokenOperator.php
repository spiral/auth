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
use Spiral\Auth\Hashes\TokenHashes;
use Spiral\Auth\LifetimeTokenOperatorInterface;
use Spiral\Auth\ORM\AbstractToken;
use Spiral\Auth\ORM\AbstractTokenSource;
use Spiral\Auth\Sources\TokenSourceInterface;
use Spiral\Auth\TokenInterface;
use Spiral\Auth\TokenOperatorInterface;
use Spiral\Auth\UserInterface;
use Spiral\Core\FactoryInterface;

abstract class AbstractTokenOperator implements TokenOperatorInterface, LifetimeTokenOperatorInterface
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
     * @var TokenHashes
     */
    protected $hashes;

    /**
     * AbstractPersistentOperator constructor.
     *
     * @param FactoryInterface $factory
     * @param int              $lifetime
     * @param string           $sourceClass
     * @param TokenHashes      $hashes
     */
    public function __construct(
        $lifetime,
        $sourceClass,
        TokenHashes $hashes
    ) {
        $this->lifetime = $lifetime;
        $this->sourceClass = $sourceClass;
        $this->hashes = $hashes;
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
     * @return TokenInterface
     */
    public function createToken(UserInterface $user)
    {
        return $this->tokenSource()->createToken($user, $this->getLifetime());
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

        $partials = $this->getTokenPartials($hash);
        if (empty($partials)) {
            return null;
        }

        list($selector, $value) = $partials;

        try {
            //Find by a selector
            if (!$authToken = $this->tokenSource()->findBySelector($selector)) {
                return null;
            }

            //Time-leaking safe comparison
            if (!$this->hashes->hashEquals($value, $authToken->getHashValue())) {
                return null;
            }

            //Check expiration
            if ($authToken->isExpired()) {
                $this->tokenSource()->deleteToken($authToken);

                return null;
            }

            $authToken->setSource($value);

            return $authToken;
        } catch (UndefinedTokenException $e) {
            return null;
        }
    }

    /**
     * Split value into parts.
     *
     * @param $value
     * @return array|bool
     */
    protected function getTokenPartials($value)
    {
        //todo delimiter may be overwritten in app
        $result = explode(TokenInterface::DELIMITER, $value);

        if (count($result) !== 2) {
            return false;
        }

        return $result;
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
     * @return TokenSourceInterface|AbstractTokenSource
     */
    protected function tokenSource()
    {
        if (empty($this->source)) {
            $this->source = $this->factory->make($this->sourceClass);
        }

        return $this->source;
    }
}