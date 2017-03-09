<?php
/**
 * Spiral Framework, SpiralScout LLC.
 *
 * @package   spiralFramework
 * @author    Anton Titov (Wolfy-J)
 * @copyright ©2009-2011
 */

namespace Spiral\Auth;

use Spiral\Auth\Exceptions\AuthException;
use Spiral\Auth\Sources\UserSourceInterface;

/**
 * @todo move user source into Operators in order to authorize multiple user types
 */
final class AuthContext implements ContextInterface
{
    /**
     * @var TokenInterface|null
     */
    private $token = null;

    /**
     * @var UserInterface|null
     */
    private $user = null;

    /**
     * @var bool
     */
    private $closed = false;

    /**
     * @invisible
     * @var UserSourceInterface
     */
    protected $users;

    /**
     * @param UserSourceInterface $users
     * @param TokenInterface|null $token
     */
    public function __construct(UserSourceInterface $users, TokenInterface $token = null)
    {
        $this->users = $users;
        $this->token = $token;
    }

    /**
     * {@inheritdoc}
     */
    public function init(TokenInterface $token)
    {
        $this->user = null;
        $this->token = $token;
        $this->closed = false;
    }

    /**
     * {@inheritdoc}
     */
    public function hasToken(): bool
    {
        return !empty($this->token);
    }

    /**
     * {@inheritdoc}
     */
    public function getToken(): TokenInterface
    {
        if (empty($this->token)) {
            throw new AuthException("Unable to get authorization token, no token is set");
        }

        return $this->token;
    }

    /**
     * {@inheritdoc}
     *
     * Attention, calling this method will attempt to load data from associated user source.
     */
    public function hasUser(): bool
    {
        return !empty($this->getUser());
    }

    /**
     * {@inheritdoc}
     */
    public function getUser()
    {
        if (empty($this->token) || $this->isClosed()) {
            return null;
        }

        if (!empty($this->user)) {
            return $this->user;
        }

        return $this->user = $this->users->findByPK($this->token->getUserPK());
    }

    /**
     * {@inheritdoc}
     */
    public function isAuthenticated(): bool
    {
        if ($this->isClosed()) {
            return false;
        }

        return $this->hasToken() || $this->hasUser();
    }

    /**
     * {@inheritdoc}
     */
    public function close()
    {
        $this->closed = true;
        $this->user = null;
    }

    /**
     * {@inheritdoc}
     */
    public function isClosed(): bool
    {
        return $this->closed;
    }
}