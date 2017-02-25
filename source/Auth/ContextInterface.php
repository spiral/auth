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
     * Example:
     * $this->auth->authenticate($user, 'cookie');
     *
     * @param UserInterface $user
     * @param string $operator Auth operator which has to handle token creation and mounting to
     *                         response.
     */
    public function authenticate(UserInterface $user, string $operator);

    //there is set operator... no get operator?

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