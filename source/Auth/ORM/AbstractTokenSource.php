<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */
namespace Spiral\Auth\ORM;

use Spiral\Auth\Exceptions\AuthException;
use Spiral\Auth\Exceptions\InvalidTokenException;
use Spiral\Auth\Exceptions\UndefinedTokenException;
use Spiral\Auth\Sources\TokenSourceInterface;
use Spiral\Auth\TokenInterface;
use Spiral\Auth\UserInterface;
use Spiral\ORM\Entities\RecordSource;
use Spiral\Support\Strings;

class AbstractTokenSource extends RecordSource implements TokenSourceInterface
{
    /**
     * @param string $hashCode
     * @return AbstractToken
     */
    public function findByHash($hashCode)
    {
        return $this->find()->findOne([
            'hashCode'        => $hashCode,
            'time_expiration' => ['<', new \DateTime('now')]
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
            throw  new UndefinedTokenException("Unable to find token associated with given hash");
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

        /*
         * Configuring token attributes.
         */
        $token->setHash($this->generateHash());
        $token->setUserPK($user->primaryKey());
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
        $this->save($token);
    }

    /**
     * @param AbstractToken $token
     * @param array         $errors
     * @return bool
     */
    public function save(AbstractToken $token, &$errors = null)
    {
        if (!$token->save()) {
            $errors = $token->getErrors();

            return false;
        }

        return true;
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
    protected function generateHash()
    {
        //Using openssl_random_pseudo_bytes
        return Strings::random(128);
    }
}