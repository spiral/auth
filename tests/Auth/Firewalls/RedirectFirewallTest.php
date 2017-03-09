<?php
/**
 * auth
 *
 * @author    Wolfy-J
 */

namespace Spiral\Tests\Auth\Firewalls;

use Spiral\Auth\Hashing\PasswordHasher;
use Spiral\Auth\Middlewares\AuthMiddleware;
use Spiral\Auth\Middlewares\Firewalls\RedirectFirewall;
use Spiral\Http\Uri;
use Spiral\Tests\HttpTest;
use TestApplication\Database\User;

class RedirectFirewallTest extends HttpTest
{
    public function testDenyAccess()
    {
        $hasher = new PasswordHasher();

        $user = new User();
        $user->username = 'username';
        $user->password_hash = $hasher->hash('password');
        $user->save();

        $this->http->pushMiddleware(AuthMiddleware::class);
        $this->http->pushMiddleware(new RedirectFirewall('https://google.com'));
        $response = $this->get('/');

        $this->assertSame(301, $response->getStatusCode());
        $this->assertSame('https://google.com', $response->getHeaderLine('Location'));
    }

    public function testDenyAccess2()
    {
        $hasher = new PasswordHasher();

        $user = new User();
        $user->username = 'username';
        $user->password_hash = $hasher->hash('password');
        $user->save();

        $this->http->pushMiddleware(AuthMiddleware::class);
        $this->http->pushMiddleware((new RedirectFirewall('/'))->withRedirect(new Uri('https://google.com')));
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
        $this->http->pushMiddleware(new RedirectFirewall('https://google.com'));
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