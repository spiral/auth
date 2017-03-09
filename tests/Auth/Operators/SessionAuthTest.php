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
use Spiral\Auth\Operators\SessionOperator;
use Spiral\Tests\HttpTest;
use TestApplication\Database\User;

class SessionAuthTest extends HttpTest
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
                $this->tokens->createToken('session', $user)
            );

            $this->assertTrue($this->auth->hasToken());
            $this->assertTrue($this->auth->hasUser());
            $this->assertFalse($this->auth->isClosed());
            $this->assertTrue($this->auth->isAuthenticated());
            $this->assertSame($user->primaryKey(), $this->auth->getUser()->primaryKey());

            //Cached
            $this->assertSame($user->primaryKey(), $this->auth->getUser()->primaryKey());

            $token = $this->auth->getToken();

            $this->assertSame('session-auth', $token->getValue());
            $this->assertInstanceOf(SessionOperator::class, $token->getOperator());
        });

        $this->http->pushMiddleware(AuthMiddleware::class);
        $this->get('/');
    }

    public function testAuthenticated()
    {
        $hasher = new PasswordHasher();

        $user = new User();
        $user->username = 'username';
        $user->password_hash = $hasher->hash('password');
        $user->save();

        $this->http->setEndpoint(function () use ($user) {
            $this->auth->init(
                $this->tokens->createToken('session', $user)
            );
        });

        $this->http->pushMiddleware(AuthMiddleware::class);
        $response = $this->get('/', [], []);

        $cookies = $this->fetchCookies($response->getHeader('Set-Cookie'));

        $this->http->setEndpoint(function () use ($user) {
            $this->assertTrue($this->auth->hasToken());
            $this->assertTrue($this->auth->hasUser());
            $this->assertTrue($this->auth->isAuthenticated());

            $this->assertSame($user->primaryKey(), $this->auth->getUser()->primaryKey());
            $this->assertSame('session-auth', $this->auth->getToken()->getValue());
        });

        $this->get('/', [], [], [
            'SID' => $cookies['SID']
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
            $this->auth->init(
                $this->tokens->createToken('session', $user)
            );
        });

        $this->http->pushMiddleware(AuthMiddleware::class);
        $response = $this->get('/', [], []);

        $cookies = $this->fetchCookies($response->getHeader('Set-Cookie'));

        $this->http->setEndpoint(function () use ($user) {
            $this->assertTrue($this->auth->hasToken());
            $this->assertTrue($this->auth->hasUser());
            $this->assertTrue($this->auth->isAuthenticated());

            $this->assertSame($user->primaryKey(), $this->auth->getUser()->primaryKey());
            $this->assertSame('session-auth', $this->auth->getToken()->getValue());

            //logout
            $this->auth->close();
        });

        $this->get('/', [], [], [
            'SID' => $cookies['SID']
        ]);

        $this->http->setEndpoint(function () use ($user) {
            $this->assertFalse($this->auth->hasToken());
            $this->assertFalse($this->auth->hasUser());
            $this->assertFalse($this->auth->isAuthenticated());
        });

        $this->get('/', [], [], [
            'SID' => $cookies['SID']
        ]);
    }
}