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
use Spiral\Auth\UserProviderInterface;
use Spiral\Core\ContainerInterface;
use Spiral\Http\MiddlewareInterface;

class AuthMiddleware implements MiddlewareInterface
{
    /**
     * @var UserProviderInterface
     */
    protected $users;

    /**
     * @var TokenManager
     */
    protected $manager;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @param UserProviderInterface $users
     * @param TokenManager          $manager
     * @param ContainerInterface    $container
     */
    public function __construct(
        UserProviderInterface $users,
        TokenManager $manager,
        ContainerInterface $container
    ) {
        $this->users = $users;
        $this->manager = $manager;
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(Request $request, Response $response, callable $next)
    {
        $context = $this->createContext($request);

        $scope = $this->container->replace(ContextInterface::class, $context);
        try {
            $response = $next($request->withAttribute('auth', $context), $response);
        } finally {
            $this->container->restore($scope);
        }

        if ($context->hasToken()) {
            $response = $this->updateToken($request, $response, $context);
        } elseif ($context->hasUser()) {
            $response = $this->createToken($request, $response, $context);
        }

        return $response;
    }

    /**
     * @param Request $request
     * @return AuthContext
     */
    protected function createContext(Request $request)
    {
        $operator = $this->manager->detectOperator($request, $name);

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
     * @param Request     $request
     * @param Response    $response
     * @param AuthContext $context
     * @return Response
     */
    private function updateToken(Request $request, Response $response, AuthContext $context)
    {
        $operator = $this->manager->getOperator($context->getOperator());

        $token = $context->getToken();
        if (!empty($token)) {
            $token->setOperator($context->getOperator());
        }

        //Session was either continued or ended.
        if ($context->isLogout()) {
            return $operator->removeToken($request, $response, $token);
        }

        return $operator->updateToken($request, $response, $token);
    }

    /**
     * @param Request     $request
     * @param Response    $response
     * @param AuthContext $context
     * @return Response
     */
    private function createToken(Request $request, Response $response, AuthContext $context)
    {
        $operator = $this->manager->getOperator($context->getOperator());
        $token = $operator->createToken($context->getUser());
        if (!empty($token)) {
            $token->setOperator($context->getOperator());
        }

        return $operator->mountToken($request, $response, $token);
    }
}