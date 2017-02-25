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
use Spiral\Auth\ContextInterface;
use Spiral\Auth\Entities\AuthContext;
use Spiral\Auth\TokenManager;
use Spiral\Auth\UserSourceInterface;
use Spiral\Core\ContainerInterface;
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
     * @param TokenManager $tokens
     * @param ScoperInterface $scopes
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
        $context = $this->createContext($request);

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
            //Refresh token state (if needed)
            $response = $this->updateToken($request, $response, $context);
        } elseif ($context->hasUser()) {
            //User was authorized inside scope, let's make sure that session persist
            $response = $this->createToken($request, $response, $context);
        }

        return $response;
    }

    /**
     * @param Request $request
     * @return AuthContext
     */
    protected function createContext(Request $request): AuthContext
    {
        //Different tokens might be handled by different operators
        $operator = $this->tokens->detectOperator($request, $name);

        if (empty($operator)) {
            return new AuthContext($this->users);
        }

        $token = $operator->fetchToken($request);
        if (!empty($token)) {
            $token->setOperator($name);
        }

        return new AuthContext($this->users, $name, $token);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param AuthContext $context
     * @return Response
     */
    private function updateToken(
        Request $request,
        Response $response,
        AuthContext $context
    ): Response {
        $operator = $this->tokens->getOperator($context->getOperator());

        $token = $context->getToken();
        if (!empty($token)) {
            $token->setOperator($context->getOperator());
        }

        //Session was either continued or ended.
        if ($context->isClosed()) {
            return $operator->removeToken($request, $response, $token);
        }

        return $operator->updateToken($request, $response, $token);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param AuthContext $context
     * @return Response
     */
    private function createToken(
        Request $request,
        Response $response,
        AuthContext $context
    ): Response {
        $operator = $this->tokens->getOperator($context->getOperator());
        $token = $operator->createToken($context->getUser());
        if (!empty($token)) {
            $token->setOperator($context->getOperator());
        }

        return $operator->mountToken($request, $response, $token);
    }
}