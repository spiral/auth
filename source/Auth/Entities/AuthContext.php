<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J), Lev Seleznev
 */
namespace Spiral\Auth\Entities;

use Spiral\Auth\TokenInterface;
use Spiral\Auth\UserInterface;
use Spiral\Auth\UserProviderInterface;

class AuthContext
{
    /**
     * @var null|TokenInterface
     */
    private $token = null;

    /**
     * @var UserInterface|null
     */
    private $user = null;

    /**
     * @var bool
     */
    private $logout = false;

    /**
     * @var string
     */
    private $provider = null;

    /**
     * @var UserProviderInterface
     */
    protected $users = null;

    /**
     * @param UserProviderInterface $users
     * @param string                $provider
     * @param TokenInterface|null   $token
     */
    public function __construct(
        UserProviderInterface $users,
        $provider = '',
        TokenInterface $token = null
    ) {
        $this->users = $users;
        $this->provider = $provider;
        $this->token = $token;
    }

    /**
     * @param TokenInterface $token
     */
    public function setToken(TokenInterface $token)
    {
        $this->token = $token;
    }

    /**
     * @return bool
     */
    public function hasToken()
    {
        return !empty($this->token);
    }

    /**
     * @return TokenInterface
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @return bool
     */
    public function isAuthenticated()
    {
        return $this->hasToken() && $this->hasUser();
    }

    /**
     * @return UserInterface|null
     */
    public function getUser()
    {
        if (!empty($this->user)) {
            return $this->user;
        }

        if (empty($this->token) || $this->logout) {
            return null;
        }

        return $this->users->getUser($this->token);
    }

    /**
     * @return bool
     */
    public function hasUser()
    {
        return !empty($this->getUser());
    }

    /**
     * @param UserInterface $user
     * @param string        $provider Auth provider which has to handle token creation.
     */
    public function authenticate(UserInterface $user, $provider)
    {
        $this->user = $user;
        $this->provider = $provider;

        $this->token = null;
        $this->logout = false;
    }

    /**
     * Mark context as logouted.
     */
    public function logout()
    {
        $this->logout = true;
    }

    /**
     * Auth provider which has to handle token creation.
     *
     * @return string
     */
    public function getProvider()
    {
        return $this->provider;
    }

    /**
     * @return bool
     */
    public function isLogout()
    {
        return $this->logout;
    }
}