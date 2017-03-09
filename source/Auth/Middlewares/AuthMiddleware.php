<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J), Lev Seleznev
 */

namespace Spiral\Auth\Middlewares;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Spiral\Auth\AuthContext;
use Spiral\Auth\ContextInterface;
use Spiral\Auth\Sources\UserSourceInterface;
use Spiral\Auth\TokenInterface;
use Spiral\Auth\TokenManager;
use Spiral\Core\ScoperInterface;
use Spiral\Http\MiddlewareInterface;

/**
 * Manages user session over database tokens. This is primary middleware which must always be set
 * before any auth firewalls.
 */
class AuthMiddleware implements MiddlewareInterface
{
    /**
     * @var UserSourceInterface
     */
    private $users;

    /**
     * @var TokenManager
     */
    private $tokens;

    /**
     * @var ScoperInterface
     */
    private $scopes;

    /**
     * @param UserSourceInterface $users
     * @param TokenManager        $tokens
     * @param ScoperInterface     $scopes
     */
    public function __construct(
        UserSourceInterface $users,
        TokenManager $tokens,
        ScoperInterface $scopes
    ) {
        $this->users = $users;
        $this->tokens = $tokens;
        $this->scopes = $scopes;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(Request $request, Response $response, callable $next)
    {
        //Contains information about current user session
        $context = new AuthContext($this->users, $this->tokens->fetchToken($request));

        $scope = $this->scopes->replace(ContextInterface::class, $context);
        try {
            $response = $next(
                $request->withAttribute('auth', $context),
                $response
            );
        } finally {
            $this->scopes->restore($scope);
        }

        if ($context->hasToken()) {
            if ($context->isClosed()) {
                //Close user session and remove associated tokens
                return $this->closeContext($request, $response, $context->getToken());
            }

            //Mount token value in response or remove it if context is closed
            return $this->commitContext($request, $response, $context->getToken());
        }

        //No user session was created
        return $response;
    }

    /**
     * @param Request        $request
     * @param Response       $response
     * @param TokenInterface $token
     *
     * @return Response
     */
    protected function commitContext(
        Request $request,
        Response $response,
        TokenInterface $token
    ): Response {
        return $token->getOperator()->updateToken($request, $response, $token);
    }

    /**
     * @param Request        $request
     * @param Response       $response
     * @param TokenInterface $token
     *
     * @return Response
     */
    protected function closeContext(
        Request $request,
        Response $response,
        TokenInterface $token
    ): Response {
        return $token->getOperator()->removeToken($request, $response, $token);
    }
}