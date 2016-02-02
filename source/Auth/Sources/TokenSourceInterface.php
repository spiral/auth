<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J), Lev Seleznev
 */
namespace Spiral\Auth\Sources;

use Spiral\Auth\TokenInterface;
use Spiral\Auth\UserInterface;

interface TokenSourceInterface
{
    /**
     * @param $hash
     * @return TokenInterface
     */
    public function getToken($hash);

    /**
     * @param $hash
     * @return bool
     */
    public function hasToken($hash);

    /**
     * @param TokenInterface $token
     */
    public function deleteToken(TokenInterface $token);

    /**
     * must automatic store token into database
     *
     * @param UserInterface $user
     * @return TokenInterface
     */
    public function createToken(UserInterface $user);

    /**
     * @param TokenInterface $token
     * @return bool
     */
    public function refreshToken(TokenInterface $token);
}