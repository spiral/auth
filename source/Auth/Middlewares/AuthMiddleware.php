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
use Spiral\Auth\TokenManager;
use Spiral\Auth\UserSourceInterface;
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
            //Refresh token state (if needed)
            return $this->updateToken($request, $response, $context);
        }

        if ($context->hasUser()) {
            //User was authorized inside scope, let's make sure that session persist
            return $this->createToken($request, $response, $context);
        }

        //Guest request
        return $response;
    }

//    /**
//     * @param Request $request
//     * @param Response $response
//     * @param AuthContext $context
//     * @return Response
//     */
//    private function updateToken(
//        Request $request,
//        Response $response,
//        AuthContext $context
//    ): Response {
//        $operator = $this->tokens->getOperator($context->getOperator());
//
//        $token = $context->getToken();
//        if (!empty($token)) {
//            $token->setOperator($context->getOperator());
//        }
//
//        //Session was either continued or ended.
//        if ($context->isClosed()) {
//            return $operator->removeToken($request, $response, $token);
//        }
//
//        return $operator->updateToken($request, $response, $token);
//    }
//
//    /**
//     * @param Request $request
//     * @param Response $response
//     * @param AuthContext $context
//     * @return Response
//     */
//    private function createToken(
//        Request $request,
//        Response $response,
//        AuthContext $context
//    ): Response {
//        $operator = $this->tokens->getOperator($context->getOperator());
//        $token = $operator->createToken($context->getUser());
//        if (!empty($token)) {
//            $token->setOperator($context->getOperator());
//        }
//
//        return $operator->mountToken($request, $response, $token);
//    }
}