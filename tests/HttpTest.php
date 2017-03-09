<?php
/**
 * spiral
 *
 * @author    Wolfy-J
 */

namespace Spiral\Tests;

use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\ServerRequest;

/**
 * @property \Spiral\Core\MemoryInterface             $memory
 * @property \Spiral\Core\ContainerInterface          $container
 * @property \Spiral\Debug\LogsInterface              $logs
 * @property \Spiral\Http\HttpDispatcher              $http
 * @property \Spiral\Console\ConsoleDispatcher        $console
 * @property \Spiral\Console\ConsoleDispatcher        $commands
 * @property \Spiral\Files\FilesInterface             $files
 * @property \Spiral\Tokenizer\TokenizerInterface     $tokenizer
 * @property \Spiral\Tokenizer\ClassesInterface       $locator
 * @property \Spiral\Tokenizer\InvocationsInterface   $invocationLocator
 * @property \Spiral\Views\ViewManager                $views
 * @property \Spiral\Translator\Translator            $translator
 * @property \Spiral\Database\DatabaseManager         $dbal
 * @property \Spiral\ORM\ORM                          $orm
 * @property \Spiral\Encrypter\EncrypterInterface     $encrypter
 * @property \Spiral\Database\Entities\Database       $db
 * @property \Spiral\Http\Cookies\CookieQueue         $cookies
 * @property \Spiral\Http\Routing\RouterInterface     $router
 * @property \Spiral\Pagination\PaginatorsInterface   $paginators
 * @property \Psr\Http\Message\ServerRequestInterface $request
 * @property \Spiral\Http\Request\InputManager        $input
 * @property \Spiral\Http\Response\ResponseWrapper    $response
 * @property \Spiral\Http\Routing\RouteInterface      $route
 * @property \Spiral\Security\PermissionsInterface    $permissions
 * @property \Spiral\Security\RulesInterface          $rules
 * @property \Spiral\Security\ActorInterface          $actor
 * @property \Spiral\Session\SessionInterface         $session
 * @property \Spiral\Auth\ContextInterface            $auth
 * @property \Spiral\Auth\TokenManager                $tokens
 */
abstract class HttpTest extends BaseTest
{
    protected function get(
        $uri,
        array $query = [],
        array $headers = [],
        array $cookies = []
    ): ResponseInterface {
        return $this->http->perform($this->request($uri, 'GET', $query, $headers, $cookies));
    }

    protected function post(
        $uri,
        array $data = [],
        array $headers = [],
        array $cookies = []
    ): ResponseInterface {
        return $this->http->perform(
            $this->request($uri, 'POST', [], $headers, $cookies)->withParsedBody($data)
        );
    }

    protected function request(
        $uri,
        string $method,
        array $query = [],
        array $headers = [],
        array $cookies = []
    ): ServerRequest {
        return new ServerRequest(
            [],
            [],
            $uri,
            $method,
            'php://input',
            $headers, $cookies,
            $query
        );
    }

    protected function fetchCookies(array $header)
    {
        $result = [];

        foreach ($header as $line) {
            $cookie = explode('=', $line);
            $result[$cookie[0]] = rawurldecode(substr($cookie[1], 0, strpos($cookie[1], ';')));
        }

        return $result;
    }
}