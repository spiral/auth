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
        $context = new AuthContext($this->users, $this->fetchToken($request));

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
     * @return TokenizerInterface|null
     */
    private function fetchToken(Request $request)
    {
        return $this->manager->fetchToken($request);
    }

    /**
     * @param Request     $request
     * @param Response    $response
     * @param AuthContext $context
     * @return Response
     */
    private function updateToken(Request $request, Response $response, AuthContext $context)
    {
        $token = $context->getToken();
        $provider = $this->manager->getProvider($token->getName());

        //Session was either continued or ended.
        if ($context->isLogout()) {
            return $provider->removeToken($request, $response, $token);
        }

        return $provider->refreshToken($request, $response, $token);
    }

    /**
     * @param Request     $request
     * @param Response    $response
     * @param AuthContext $context
     * @return Response
     */
    private function createToken(Request $request, Response $response, AuthContext $context)
    {
        $provider = $this->manager->getProvider($context->requestedProvider());

        return $provider->mountToken(
            $request,
            $response,
            $this->manager->createToken($context->requestedProvider(), $context->getUser())
        );
    }
}