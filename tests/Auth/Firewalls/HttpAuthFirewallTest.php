<?php
/**
 * auth
 *
 * @author    Wolfy-J
 */

namespace Spiral\Tests\Auth\Firewalls;

use Spiral\Auth\Hashing\PasswordHasher;
use Spiral\Auth\Middlewares\AuthMiddleware;
use Spiral\Auth\Middlewares\Firewalls\HttpAuthFirewall;
use Spiral\Tests\HttpTest;
use TestApplication\Database\User;

class HttpAuthFirewallTest extends HttpTest
{
    public function testDenyAccess()
    {
        $hasher = new PasswordHasher();

        $user = new User();
        $user->username = 'username';
        $user->password_hash = $hasher->hash('password');
        $user->save();

        $this->http->pushMiddleware(AuthMiddleware::class);
        $this->http->pushMiddleware(HttpAuthFirewall::class);
        $response = $this->get('/');

        $this->assertSame(401, $response->getStatusCode());
        $this->assertSame('Basic realm="Login"', $response->getHeaderLine('WWW-Authenticate'));
    }

    public function testAllowAccess()
    {
        $hasher = new PasswordHasher();

        $user = new User();
        $user->username = 'username';
        $user->password_hash = $hasher->hash('password');
        $user->save();

        $this->http->pushMiddleware(AuthMiddleware::class);
        $this->http->pushMiddleware(HttpAuthFirewall::class);
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