<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J), Lev Seleznev
 */
namespace Spiral\Auth;

interface ContextInterface
{
    /**
     * @return bool
     */
    public function hasToken();

    /**
     * @return TokenInterface
     */
    public function getToken();

    /**
     * @return bool
     */
    public function hasUser();

    /**
     * @return UserInterface
     */
    public function getUser();

    /**
     * @param UserInterface $user
     * @param string        $operator Auth operator which has to handle token creation and mounting
     *                                to response.
     */
    public function authenticate(UserInterface $user, $operator);

    /**
     * @return bool
     */
    public function isAuthenticated();

    /**
     * Mark context as logged out.
     */
    public function logout();

    /**
     * @return bool
     */
    public function isLogout();
}