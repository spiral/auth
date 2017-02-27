<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J), Lev Seleznev
 */

namespace Spiral\Auth;

use Spiral\Auth\Exceptions\AuthException;

interface ContextInterface
{
    /**
     * @return bool
     */
    public function hasToken(): bool;

    /**
     * Init authentication token using given token. Example:
     * $this->auth->init(
     *      $this->tokens->createToken('cookies', $user)
     * );
     *
     * Attention, method will overwrite already existed user and token. Use close() method to remove
     * previously set token and de-auth user.
     *
     * @param TokenInterface $token
     */
    public function init(TokenInterface $token);

    /**
     * @return TokenInterface
     * @throws AuthException When no token are set.
     */
    public function getToken(): TokenInterface;

    /**
     * @return bool
     */
    public function hasUser(): bool;

    /**
     * @return UserInterface
     * @throws AuthException When no user are set.
     */
    public function getUser(): UserInterface;

    /**
     * @return bool
     */
    public function isAuthenticated(): bool;

    /**
     * Mark context as closed, all user tokens must be removed. Context might be closed but still
     * has authenticated user.
     */
    public function close();

    /**
     * Indication that context was closed.
     *
     * @return bool
     */
    public function isClosed(): bool;
}