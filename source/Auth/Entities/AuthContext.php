<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J), Lev Seleznev
 */
namespace Spiral\Auth\Entities;

use Spiral\Auth\ContextInterface;
use Spiral\Auth\TokenInterface;
use Spiral\Auth\UserInterface;
use Spiral\Auth\UserProviderInterface;

class AuthContext implements ContextInterface
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
    private $operator;

    /**
     * @var UserProviderInterface
     */
    protected $users;

    /**
     * @param UserProviderInterface $users
     * @param string                $operator
     * @param TokenInterface|null   $token
     */
    public function __construct(
        UserProviderInterface $users,
        $operator = '',
        TokenInterface $token = null
    ) {
        $this->users = $users;
        $this->operator = $operator;
        $this->token = $token;
    }

    /**
     * {@inheritdoc}
     */
    public function setToken(TokenInterface $token)
    {
        $this->token = $token;
    }

    /**
     * {@inheritdoc}
     */
    public function hasToken()
    {
        return !empty($this->token);
    }

    /**
     * {@inheritdoc}
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * {@inheritdoc}
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

        return $this->user = $this->users->getUser($this->token);
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
     * @param string        $operator Auth operator which has to handle token creation and mounting
     *                                to response.
     */
    public function authenticate(UserInterface $user, $operator)
    {
        $this->user = $user;
        $this->operator = $operator;

        $this->token = null;
        $this->logout = false;
    }

    /**
     * {@inheritdoc}
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
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * {@inheritdoc}
     */
    public function isLogout()
    {
        return $this->logout;
    }
}
