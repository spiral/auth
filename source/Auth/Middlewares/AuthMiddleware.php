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
use Spiral\Auth\Entities\AuthContext;
use Spiral\Auth\TokenManager;
use Spiral\Auth\UserProviderInterface;
use Spiral\Http\MiddlewareInterface;
use Spiral\Tokenizer\TokenizerInterface;

class AuthMiddleware implements MiddlewareInterface
{
    /**
     * @var UserProviderInterface
     */
    private $users;

    /**
     * @var TokenManager
     */
    private $manager;

    /**
     * @param UserProviderInterface $users
     * @param TokenManager          $manager
     */
    public function __construct(UserProviderInterface $users, TokenManager $manager)
    {
        $this->users = $users;
        $this->manager = $manager;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(Request $request, Response $response, callable $next)
    {
        $context = $this->createContext($request);

        $response = $next(
            $request->withAttribute('auth', $context),
            $response
        );

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
    private function createContext(Request $request)
    {
        $provider = $this->manager->detectProvider($request);

        if (empty($provider)) {
            return new AuthContext($this->users);
        }

        return new AuthContext(
            $this->users,
            $provider,
            $this->manager->getProvider($provider)->fetchToken($request)
        );
    }

    /**
     * @param Request     $request
     * @param Response    $response
     * @param AuthContext $context
     * @return Response
     */
    private function updateToken(Request $request, Response $response, AuthContext $context)
    {
        $provider = $this->manager->getProvider($context->getProvider());

        //Session was either continued or ended.
        if ($context->isLogout()) {
            return $provider->removeToken($request, $response, $context->getToken());
        }

        return $provider->refreshToken($request, $response, $context->getToken());
    }

    /**
     * @param Request     $request
     * @param Response    $response
     * @param AuthContext $context
     * @return Response
     */
    private function createToken(Request $request, Response $response, AuthContext $context)
    {
        $provider = $this->manager->getProvider($context->getProvider());

        return $provider->mountToken(
            $request,
            $response,
            $provider->createToken($context->getUser())
        );
    }
}