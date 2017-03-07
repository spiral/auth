<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J), Lev Seleznev
 */

namespace Spiral\Auth\Sources;

use Spiral\Auth\Exceptions\UndefinedTokenException;
use Spiral\Auth\TokenInterface;
use Spiral\Auth\UserInterface;

/**
 * Provides ability to store and fetch tokens from persistent location like database or file.
 */
interface TokenSourceInterface
{
    /**
     * Find token in persistent storage or return null.
     *
     * @param string $hash
     * @return TokenInterface|null
     */
    public function findToken(string $hash);

    /**
     * Must return already stored token.
     *
     * @param UserInterface $user
     * @param  int          $lifetime
     * @return TokenInterface
     */
    public function createToken(UserInterface $user, int $lifetime): TokenInterface;

    /**
     * Touch token (refresh lifetime and other values if needed).
     *
     * @param TokenInterface $token
     * @param  int           $lifetime
     * @return bool
     */
    public function touchToken(TokenInterface $token, int $lifetime);

    /**
     * @param TokenInterface $token
     */
    public function deleteToken(TokenInterface $token);
}