<?php
/**
 * auth
 *
 * @author    Wolfy-J
 */

namespace Spiral\Tests\Auth\Firewalls;

use Spiral\Auth\Hashing\PasswordHasher;
use Spiral\Auth\Middlewares\AuthMiddleware;
use Spiral\Auth\Middlewares\Firewalls\ExceptionFirewall;
use Spiral\Tests\HttpTest;
use TestApplication\Database\User;

class RedirectFirewallTest extends HttpTest
{
    /**
     * @expectedException \Spiral\Http\Exceptions\ClientExceptions\ForbiddenException
     */
    public function testDenyAccess()
    {
        $hasher = new PasswordHasher();

        $user = new User();
        $user->username = 'username';
        $user->password_hash = $hasher->hash('password');
        $user->save();

        $this->http->pushMiddleware(AuthMiddleware::class);
        $this->http->pushMiddleware(ExceptionFirewall::class);
        $response = $this->get('/');

        $this->assertSame(301, $response->getStatusCode());
        $this->assertSame('https://google.com', $response->getHeaderLine('Location'));
    }

    public function testAllowAccess()
    {
        $hasher = new PasswordHasher();

        $user = new User();
        $user->username = 'username';
        $user->password_hash = $hasher->hash('password');
        $user->save();

        $this->http->pushMiddleware(AuthMiddleware::class);
        $this->http->pushMiddleware(ExceptionFirewall::class);
        $response = $this->get('/', [], [
            'Authorization' => $this->authHeader('username', 'password')
        ]);

        $this->assertSame(200, $response->getStatusCode());
    }

    private function authHeader($username, $password)
    {
        return 'Basic ' . base64_encode("{$username}:{$password}");
    }
}