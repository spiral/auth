<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J), Lev Seleznev
 */
namespace Spiral\Auth\Authenticators;

use Spiral\Auth\Exceptions\InvalidUserException;
use Spiral\Auth\Hashes\PasswordHashes;
use Spiral\Auth\PasswordAwareInterface;
use Spiral\Auth\Sources\UsernameSourceInterface;

class CredentialsAuthenticator
{
    /**
     * @var UsernameSourceInterface
     */
    private $source;

    /**
     * @var PasswordHashes
     */
    private $hashes;

    /**
     * CredentialsAuthenticator constructor.
     *
     * @param UsernameSourceInterface $source
     * @param PasswordHashes          $hashes
     */
    public function __construct(UsernameSourceInterface $source, PasswordHashes $hashes)
    {
        $this->source = $source;
        $this->hashes = $hashes;
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

        if ($this->hashes->hashEquals($password, $user->getPasswordHash())) {
            //Password needs rehash logic dedicated to user application
            return $user;
        }

        return null;
    }
}