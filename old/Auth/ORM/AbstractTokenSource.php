<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Auth\ORM;

use Spiral\Auth\Configs\AuthConfig;
use Spiral\Auth\Exceptions\AuthException;
use Spiral\Auth\Exceptions\InvalidTokenException;
use Spiral\Auth\Exceptions\UndefinedTokenException;
use Spiral\Auth\Hashes\TokenHashes;
use Spiral\Auth\RandomGenerator;
use Spiral\Auth\Sources\TokenSourceInterface;
use Spiral\Auth\TokenInterface;
use Spiral\Auth\UserInterface;
use Spiral\Database\Builders\Prototypes\AbstractSelect;
use Spiral\ORM\Entities\RecordSelector;
use Spiral\ORM\Entities\RecordSource;
use Spiral\ORM\Exceptions\SourceException;
use Spiral\ORM\ORM;
use Spiral\Support\Strings;

abstract class AbstractTokenSource extends RecordSource implements TokenSourceInterface
{
    /**
     * @var RandomGenerator
     */
    protected $generator = null;

    /**
     * @var TokenHashes
     */
    protected $hashes = null;

    /**
     * @param string          $class
     * @param ORM             $orm
     * @param RandomGenerator $generator
     * @param TokenHashes     $hashes
     * @param AuthConfig      $config
     */
    public function __construct(
        $class = null,
        ORM $orm = null,
        RandomGenerator $generator,
        TokenHashes $hashes,
        AuthConfig $config
    ) {
        parent::__construct($class, $orm);

        $this->hashes = $hashes;
        $this->generator = $generator;
    }

    /**
     * @param string $selector
     * @return null|\Spiral\ORM\RecordEntity|AbstractToken
     */
    public function findBySelector($selector)
    {
        return $this->findOne(compact('selector'));
    }

    /**
     * @param string $hash
     * @return AbstractToken
     */
    public function findByHash($hash)
    {
        return $this->find()->findOne([
            'hash' => $hash
        ]);
    }

    /**
     * @param string $hash
     * @return TokenInterface
     * @throws UndefinedTokenException
     */
    public function getToken($hash)
    {
        if (empty($token = $this->findByHash($hash))) {
            throw new UndefinedTokenException("Unable to find token associated with given hash");
        }

        return $token;
    }

    /**
     * @param TokenInterface $token
     */
    public function deleteToken(TokenInterface $token)
    {
        if (get_class($token) != static::RECORD || !$token instanceof AbstractToken) {
            throw new InvalidTokenException("Only instances of " . static::RECORD . " is allowed.");
        }

        $this->delete($token);
    }

    /**
     * must automatic store token into database
     *
     * @param UserInterface $user
     * @param int           $lifetime
     * @return TokenInterface
     */
    public function createToken(UserInterface $user, $lifetime)
    {
        /**
         * @var AbstractToken $token
         */
        $token = $this->create();

        $code = $this->generateHash();
        $token->setSource($code);

        /*
         * Configuring token attributes.
         */
        $token->setUserPK($user->primaryKey());
        $token->setField('hash', $this->hashes->makeHash($code));
        $token->setField('selector', $this->generateSelector());
        $token->setExpiration(new \DateTime("now + {$lifetime} seconds"));

        if (!$this->save($token)) {
            throw new AuthException("Unable to save token to database");
        }

        return $token;
    }

    /**
     * @param TokenInterface $token
     * @param int            $lifetime
     * @return bool
     */
    public function updateToken(TokenInterface $token, $lifetime)
    {
        if (get_class($token) != static::RECORD || !$token instanceof AbstractToken) {
            throw new InvalidTokenException("Only instances of " . static::RECORD . " is allowed.");
        }

        $token->setExpiration(new \DateTime("now + {$lifetime} seconds"));

        if (!$this->save($token)) {
            throw new AuthException("Unable to save token to database");
        }
    }

    /**
     * @param AbstractToken $token
     * @param array         $errors
     * @return bool
     */
    public function store(AbstractToken $token)
    {

    }

    /**
     * @param AbstractToken $token
     */
    public function delete(AbstractToken $token)
    {
        $token->delete();
    }

    /**
     * @return string
     */
    public function generateHash()
    {
        return $this->generator->generateTokenHash();
    }

    /**
     * @param string|null $passedSelector
     * @return string
     */
    public function generateSelector($passedSelector = null)
    {
        $selector = $this->generator->generateTokenSelector();

        //Selector is not unique
        if (strcasecmp($selector, $passedSelector) === 0) {
            return $this->generateSelector($selector);
        }

        //Selector is not unique
        if ($this->findBySelector($selector)) {
            return $this->generateSelector($selector);
        }

        return $selector;
    }
}