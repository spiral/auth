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

interface TokenSourceInterface
{
    /**
     * @param string $hash
     * @return TokenInterface
     * @throws UndefinedTokenException
     */
    public function getToken($hash);

    /**
     * Must return already stored token.
     *
     * @param UserInterface $user
     * @param  int          $lifetime
     * @return TokenInterface
     */
    public function createToken(UserInterface $user, $lifetime);

    /**
     * @param TokenInterface $token
     * @param  int           $lifetime
     * @return bool
     */
    public function updateToken(TokenInterface $token, $lifetime);

    /**
     * @param TokenInterface $token
     */
    public function deleteToken(TokenInterface $token);

    /**
     * @param string $selector
     * @return TokenInterface
     * @throws UndefinedTokenException
     */
    public function findBySelector($selector);
}