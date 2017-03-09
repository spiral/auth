<?php
/**
 * auth
 *
 * @author    Wolfy-J
 */

namespace Spiral\Tests\Auth\Operators;

use Spiral\Auth\Hashing\PasswordHasher;
use Spiral\Tests\HttpTest;
use TestApplication\Database\User;

class PersistentAuthTest extends HttpTest
{
    public function testCreateToken()
    {
        $hasher = new PasswordHasher();

        $user = new User();
        $user->username = 'username';
        $user->password_hash = $hasher->hash('password');
        $user->save();

        $this->tokens->createToken($user)->getValue();
    }
}