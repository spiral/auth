<?php
/**
 * auth
 *
 * @author    Wolfy-J
 */

namespace Spiral\Tests\Auth;

use Spiral\Auth\Authenticators\CredentialsAuthenticator;
use Spiral\Auth\Hashing\PasswordHasher;
use Spiral\Tests\BaseTest;
use TestApplication\Database\User;

class CredentialsTest extends BaseTest
{
    /**
     * @expectedException \Spiral\Auth\Exceptions\CredentialsException
     * @expectedExceptionMessage Unable to authorize, no such user
     */
    public function testInvalidUsername()
    {
        /** @var \Spiral\Auth\Authenticators\CredentialsAuthenticator $authenticator */
        $authenticator = $this->container->make(CredentialsAuthenticator::class);

        $authenticator->getUser('username', 'password');
    }

    /**
     * @expectedException \Spiral\Auth\Exceptions\CredentialsException
     * @expectedExceptionMessage Unable to authorize, invalid password
     */
    public function testInvalidPassword()
    {
        $hasher = new PasswordHasher();

        $user = new User();
        $user->username = 'username';
        $user->password_hash = $hasher->hash('password');
        $user->save();

        /** @var \Spiral\Auth\Authenticators\CredentialsAuthenticator $authenticator */
        $authenticator = $this->container->make(CredentialsAuthenticator::class);

        $authenticator->getUser('username', 'invalid-password');
    }

    public function testAuthorize()
    {
        $hasher = new PasswordHasher();

        $user = new User();
        $user->username = 'username';
        $user->password_hash = $hasher->hash('password');
        $user->save();

        /** @var \Spiral\Auth\Authenticators\CredentialsAuthenticator $authenticator */
        $authenticator = $this->container->make(CredentialsAuthenticator::class);

        $user = $authenticator->getUser('username', 'password');

        $this->assertInstanceOf(User::class, $user);
    }
}