<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J), Lev Seleznev
 */
namespace Spiral\Auth;


interface AuthContextInterface
{
    /**
     * @param TokenInterface $token
     */
    public function setToken(TokenInterface $token);

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
    public function isAuthenticated();

    /**
     * Mark context as logouted.
     */
    public function logout();

    /**
     * @return bool
     */
    public function isLogout();
}