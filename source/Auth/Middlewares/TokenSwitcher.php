<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */
namespace Spiral\Auth\Middlewares;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Spiral\Auth\Entities\AuthContext;
use Spiral\Http\MiddlewareInterface;

/**
 * Provides ability to re-authenticate user using specified operator.
 */
class TokenSwitcher implements MiddlewareInterface
{
    /**
     * Source operator.
     *
     * @var string
     */
    private $from;

    /**
     * Replacement operator.
     *
     * @var string
     */
    private $replace;

    /**
     * Example: (cookie, session), (basic, session)
     *
     * @param string $from
     * @param string $replace
     */
    public function __construct($from, $replace)
    {
        $this->from = $from;
        $this->replace = $replace;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(Request $request, Response $response, callable $next)
    {
        $auth = $request->getAttribute('auth');

        if ($auth instanceof AuthContext && $auth->isAuthenticated()) {
            $response = $next($request, $response);

            if ($auth->hasToken() && $auth->getOperator() == $this->from) {
                //Re-authorize using replacement operator
                $auth->authenticate($auth->getUser(), $this->replace);
            }

            return $response;
        }

        return $next($request, $response);
    }
}