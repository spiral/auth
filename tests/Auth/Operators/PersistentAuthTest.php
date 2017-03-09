<?php
/**
 * auth
 *
 * @author    Wolfy-J
 */

namespace Spiral\Tests\Auth\Operators;

use Spiral\Auth\Database\AuthToken;
use Spiral\Auth\Hashing\PasswordHasher;
use Spiral\Auth\Middlewares\AuthMiddleware;
use Spiral\Auth\Operators\PersistentOperator;
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

        $this->assertSame(0, $this->dbal->database('auth')->auth_tokens->count());
        $this->tokens->createToken($user)->getValue();
        $this->assertSame(1, $this->dbal->database('auth')->auth_tokens->count());
    }

    public function testLoginByTokenHeader()
    {
        $hasher = new PasswordHasher();

        $user = new User();
        $user->username = 'username';
        $user->password_hash = $hasher->hash('password');
        $user->save();

        $tokenValue = $this->tokens->createToken($user)->getValue();

        $this->http->setEndpoint(function () use ($user, $tokenValue) {
            $this->assertTrue($this->auth->hasToken());
            $this->assertTrue($this->auth->hasUser());

            $this->assertFalse($this->auth->isClosed());

            $this->assertTrue($this->auth->isAuthenticated());
            $this->assertSame($user->primaryKey(), $this->auth->getUser()->primaryKey());

            $token = $this->auth->getToken();

            $this->assertSame($tokenValue, $token->getValue());
            $this->assertInstanceOf(AuthToken::class, $token);
            $this->assertInstanceOf(PersistentOperator::class, $token->getOperator());
        });

        $this->http->pushMiddleware(AuthMiddleware::class);
        $this->get('/', [], [
            'X-Auth-Token' => $tokenValue
        ]);
    }

    public function testLoginByCookie()
    {
        $hasher = new PasswordHasher();

        $user = new User();
        $user->username = 'username';
        $user->password_hash = $hasher->hash('password');
        $user->save();

        $tokenValue = $this->tokens->createToken($user)->getValue();

        $this->http->setEndpoint(function () use ($user, $tokenValue) {
            $this->assertTrue($this->auth->hasToken());
            $this->assertTrue($this->auth->hasUser());

            $this->assertFalse($this->auth->isClosed());

            $this->assertTrue($this->auth->isAuthenticated());
            $this->assertSame($user->primaryKey(), $this->auth->getUser()->primaryKey());

            $token = $this->auth->getToken();

            $this->assertSame($tokenValue, $token->getValue());
            $this->assertInstanceOf(AuthToken::class, $token);
            $this->assertInstanceOf(PersistentOperator::class, $token->getOperator());
        });

        $this->http->pushMiddleware(AuthMiddleware::class);
        $this->get('/', [], [], [
            'auth-token' => $tokenValue
        ]);
    }

    public function testAuthorizeByCookie()
    {
        $hasher = new PasswordHasher();

        $user = new User();
        $user->username = 'username';
        $user->password_hash = $hasher->hash('password');
        $user->save();

        $this->http->setEndpoint(function () use ($user) {
            $this->auth->init(
                $this->tokens->createToken($user)
            );

            $this->assertTrue($this->auth->hasToken());
            $this->assertTrue($this->auth->hasUser());

            $this->assertFalse($this->auth->isClosed());

            $this->assertTrue($this->auth->isAuthenticated());
            $this->assertSame($user->primaryKey(), $this->auth->getUser()->primaryKey());

            $token = $this->auth->getToken();

            $this->assertInstanceOf(AuthToken::class, $token);
            $this->assertInstanceOf(PersistentOperator::class, $token->getOperator());
        });

        $this->http->pushMiddleware(AuthMiddleware::class);
        $response = $this->get('/', [], [], []);

        $this->assertArrayHasKey('auth-token',
            $this->fetchCookies($response->getHeader('Set-Cookie'))
        );
    }

    public function testAuthorizeByHeader()
    {
        $hasher = new PasswordHasher();

        $user = new User();
        $user->username = 'username';
        $user->password_hash = $hasher->hash('password');
        $user->save();

        $this->http->setEndpoint(function () use ($user) {
            $this->auth->init(
                $this->tokens->createToken($user, 'header')
            );

            $this->assertTrue($this->auth->hasToken());
            $this->assertTrue($this->auth->hasUser());

            $this->assertFalse($this->auth->isClosed());

            $this->assertTrue($this->auth->isAuthenticated());
            $this->assertSame($user->primaryKey(), $this->auth->getUser()->primaryKey());

            $token = $this->auth->getToken();

            $this->assertInstanceOf(AuthToken::class, $token);
            $this->assertInstanceOf(PersistentOperator::class, $token->getOperator());
        });

        $this->http->pushMiddleware(AuthMiddleware::class);
        $response = $this->get('/', [], [], []);

        $this->assertTrue($response->hasHeader('X-Auth-Token'));
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
                $this->tokens->createToken($user, 'header')
            );

            $this->assertTrue($this->auth->hasToken());
            $this->assertTrue($this->auth->hasUser());

            $this->assertFalse($this->auth->isClosed());

            $this->assertTrue($this->auth->isAuthenticated());
            $this->assertSame($user->primaryKey(), $this->auth->getUser()->primaryKey());

            $token = $this->auth->getToken();

            $this->assertInstanceOf(AuthToken::class, $token);
            $this->assertInstanceOf(PersistentOperator::class, $token->getOperator());
        });

        $this->http->pushMiddleware(AuthMiddleware::class);
        $response = $this->get('/', [], [], []);

        $this->assertSame(1, $this->dbal->database('auth')->auth_tokens->count());


        $this->assertTrue($response->hasHeader('X-Auth-Token'));

        $this->http->setEndpoint(function () use ($user) {
            $this->auth->close();
        });

        $response = $this->get('/', [], [
            'X-Auth-Token' => $response->getHeaderLine('X-Auth-Token')
        ], []);

        $this->assertSame(0, $this->dbal->database('auth')->auth_tokens->count());
    }
}