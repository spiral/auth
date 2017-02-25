<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J), Lev Seleznev
 */
namespace Spiral\Auth;

interface UserProviderInterface
{
    /**
     * Must return user associated with previously created token.
     *
     *
     * @param TokenInterface $token
     * @return UserInterface
     */
    public function getUser(TokenInterface $token): UserInterface;
}