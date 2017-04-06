<?php
/**
 * Spiral Framework, SpiralScout LLC.
 *
 * @package   spiralFramework
 * @author    Anton Titov (Wolfy-J)
 * @copyright ©2009-2011
 */

namespace Spiral\Tests\Auth;

use Spiral\Auth\Middlewares\AuthMiddleware;
use Spiral\Tests\HttpTest;

class TouchTest extends HttpTest
{
    //Verification test
    public function testDave()
    {
        $response = $this->get('/');

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('Hello, Dave.', (string)$response->getBody());
    }

    public function testWithAuthMiddleware()
    {
        $this->http->pushMiddleware(AuthMiddleware::class);

        $response = $this->get('/');

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('Hello, Dave.', (string)$response->getBody());
    }
}