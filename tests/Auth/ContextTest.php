<?php
/**
 * auth
 *
 * @author    Wolfy-J
 */

namespace Spiral\Tests\Auth;

use Spiral\Auth\ContextInterface;
use Spiral\Auth\Middlewares\AuthMiddleware;
use Spiral\Auth\TokenManager;
use Spiral\Tests\HttpTest;

class ContextTest extends HttpTest
{
    public function testContext()
    {
        $this->http->setEndpoint(function () {
            $this->assertInstanceOf(ContextInterface::class, $this->auth);
            $this->assertInstanceOf(TokenManager::class, $this->tokens);
        });

        $this->http->pushMiddleware(AuthMiddleware::class);
        $this->get('/');
    }

    public function testNotAuthorized()
    {
        $this->http->setEndpoint(function () {
            $this->assertFalse($this->auth->hasToken());
            $this->assertFalse($this->auth->hasUser());
            $this->assertFalse($this->auth->isClosed());
            $this->assertFalse($this->auth->isAuthenticated());
        });

        $this->http->pushMiddleware(AuthMiddleware::class);
        $this->get('/');
    }

    /**
     * @expectedException \Spiral\Auth\Exceptions\AuthException
     */
    public function testGetTokenWhenNoToken()
    {
        $this->http->setEndpoint(function () {
            $this->auth->getToken();
        });

        $this->http->pushMiddleware(AuthMiddleware::class);
        $this->get('/');
    }
}