<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J), Lev Seleznev
 */
namespace Spiral\Auth\Bootloaders;

use Psr\Http\Message\ServerRequestInterface;
use Spiral\Auth\Configs\AuthConfig;
use Spiral\Auth\Entities\AuthContext;
use Spiral\Auth\Exceptions\AuthException;
use Spiral\Auth\UserSourceInterface;
use Spiral\Auth\Sources\UsernameUserSourceInterface;
use Spiral\Auth\UserProvider;
use Spiral\Auth\UserProviderInterface;
use Spiral\Core\Bootloaders\Bootloader;
use Spiral\Core\Exceptions\Container\AutowireException;
use Spiral\Core\Exceptions\SugarException;
use Spiral\Core\FactoryInterface;

class AuthBootloader extends Bootloader
{
    /**
     * @var array
     */
    protected $singletons = [
        //Default user provider
        UserProviderInterface::class       => UserProvider::class,

        //Default source linking
        UsernameUserSourceInterface::class => UserSourceInterface::class,

        //Automatically resolved thought configuration
        UserSourceInterface::class         => [self::class, 'userSource'],

        //Auth Context resolver
        AuthContext::class                 => [self::class, 'authContext'],

        //Shortcut
        'auth'                             => AuthContext::class
    ];

    /**
     * @param AuthConfig       $config
     * @param FactoryInterface $factory
     * @return UserSourceInterface
     */
    public function userSource(AuthConfig $config, FactoryInterface $factory)
    {
        if (empty($config->userSource())) {
            throw new AuthException("User source is not set");
        }

        return $factory->make($config->userSource());
    }

    /**
     * @param ServerRequestInterface $request
     * @return AuthContext
     * @throws AutowireException
     * @throws SugarException
     */
    public function authContext(ServerRequestInterface $request)
    {
        if (empty($request)) {
            throw new AutowireException("No active request found");
        }

        $auth = $request->getAttribute('auth');

        if (!$auth instanceof AuthContext) {
            throw new SugarException("Unable to resolve auth context using active request");
        }

        return $auth;
    }
}