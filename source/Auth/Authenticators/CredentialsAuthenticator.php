<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J), Lev Seleznev
 */
namespace Spiral\Auth\Authenticators;

use Spiral\Auth\Exceptions\InvalidUserException;
use Spiral\Auth\PasswordAwareInterface;
use Spiral\Auth\Sources\UsernameSourceInterface;

class CredentialsAuthenticator
{
    /** @var UsernameSourceInterface */
    private $source;

    /**
     * @param UsernameSourceInterface $source
     */
    public function __construct(UsernameSourceInterface $source)
    {
        $this->source = $source;
    }

    /**
     * @param string $username
     * @param string $password
     * @return null|PasswordAwareInterface
     */
    public function getUser($username, $password)
    {
        $user = $this->source->findByUsername($username);
        if (empty($user)) {
            return null;
        }

        if (!$user instanceof PasswordAwareInterface) {
            throw new InvalidUserException("User must be instance of PasswordAwareInterface");
        }

        if (password_verify($password, $user->getPasswordHash())) {
            //Password needs rehash logic dedicated to user application
            return $user;
        }

        return null;
    }
}