<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J), Lev Seleznev
 */

namespace Spiral\Tests\Auth\Operators;

use Spiral\Auth\Hashing\PasswordHasher;
use Spiral\Auth\Middlewares\AuthMiddleware;
use Spiral\Auth\Operators\HttpOperator;
use Spiral\Tests\HttpTest;
use TestApplication\Database\User;

class HttpAuthTest extends HttpTest
{
    public function testCreateToken()
    {
        $hasher = new PasswordHasher();

        $user = new User();
        $user->username = 'username';
        $user->password_hash = $hasher->hash('password');
        $user->save();

        $this->http->setEndpoint(function () use ($user) {
            $this->auth->init(
                $this->tokens->createToken('basic', $user)
            );

            $this->assertTrue($this->auth->hasToken());
            $this->assertTrue($this->auth->hasUser());
            $this->assertFalse($this->auth->isClosed());
            $this->assertTrue($this->auth->isAuthenticated());
            $this->assertSame($user->primaryKey(), $this->auth->getUser()->primaryKey());

            //Cached
            $this->assertSame($user->primaryKey(), $this->auth->getUser()->primaryKey());

            $token = $this->auth->getToken();

            $this->assertSame('http-auth', $token->getValue());
            $this->assertInstanceOf(HttpOperator::class, $token->getOperator());
        });

        $this->http->pushMiddleware(AuthMiddleware::class);
        $this->get('/');
    }

    public function testBadAuthenticate()
    {
        $hasher = new PasswordHasher();

        $user = new User();
        $user->username = 'username';
        $user->password_hash = $hasher->hash('password');
        $user->save();

        $this->http->setEndpoint(function () use ($user) {
            $this->assertFalse($this->auth->hasToken());
            $this->assertFalse($this->auth->hasUser());
            $this->assertFalse($this->auth->isAuthenticated());
        });

        $this->http->pushMiddleware(AuthMiddleware::class);
        $this->get('/', [], [
            'Authorization' => 'invalid'
        ]);
    }

    public function testBadAuthenticate2()
    {
        $hasher = new PasswordHasher();

        $user = new User();
        $user->username = 'username';
        $user->password_hash = $hasher->hash('password');
        $user->save();

        $this->http->setEndpoint(function () use ($user) {
            $this->assertFalse($this->auth->hasToken());
            $this->assertFalse($this->auth->hasUser());
            $this->assertFalse($this->auth->isAuthenticated());
        });

        $this->http->pushMiddleware(AuthMiddleware::class);
        $this->get('/', [], [
            'Authorization' => $this->authHeader('username', 'invalid-password')
        ]);
    }

    public function testAuthenticate()
    {
        $hasher = new PasswordHasher();

        $user = new User();
        $user->username = 'username';
        $user->password_hash = $hasher->hash('password');
        $user->save();

        $this->http->setEndpoint(function () use ($user) {
            $this->assertTrue($this->auth->hasToken());
            $this->assertTrue($this->auth->hasUser());

            $this->assertFalse($this->auth->isClosed());

            $this->assertTrue($this->auth->isAuthenticated());
            $this->assertSame($user->primaryKey(), $this->auth->getUser()->primaryKey());

            $token = $this->auth->getToken();

            $this->assertSame('http-auth', $token->getValue());
            $this->assertInstanceOf(HttpOperator::class, $token->getOperator());
        });

        $this->http->pushMiddleware(AuthMiddleware::class);
        $this->get('/', [], [
            'Authorization' => $this->authHeader('username', 'password')
        ]);
    }

    public function testLogout()
    {
        $hasher = new PasswordHasher();

        $user = new User();
        $user->username = 'username';
        $user->password_hash = $hasher->hash('password');
        $user->save();

        $this->http->setEndpoint(function () use ($user) {
            $this->assertTrue($this->auth->hasToken());
            $this->assertTrue($this->auth->hasUser());

            $this->assertFalse($this->auth->isClosed());

            $this->assertTrue($this->auth->isAuthenticated());
            $this->assertSame($user->primaryKey(), $this->auth->getUser()->primaryKey());

            $this->auth->close();
            $this->assertTrue($this->auth->isClosed());

            $this->assertFalse($this->auth->isAuthenticated());
            $this->assertFalse($this->auth->hasUser());

            $this->assertNull($this->auth->getUser());
        });

        $this->http->pushMiddleware(AuthMiddleware::class);
        $this->get('/', [], [
            'Authorization' => $this->authHeader('username', 'password')
        ]);
    }

    private function authHeader($username, $password)
    {
        return 'Basic ' . base64_encode("{$username}:{$password}");
    }
}