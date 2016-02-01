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
use Spiral\Auth\SourceInterface;
use Spiral\Auth\Sources\UsernameSourceInterface;
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
        UserProviderInterface::class   => UserProvider::class,

        //Default source linking
        UsernameSourceInterface::class => SourceInterface::class,

        //Automatically resolved thought configuration
        SourceInterface::class         => [self::class, 'userSource'],

        //Auth Context resolver
        AuthContext::class             => [self::class, 'authContext'],

        //Shortcut
        'auth'                         => AuthContext::class
    ];

    /**
     * @param AuthConfig       $config
     * @param FactoryInterface $factory
     * @return SourceInterface
     */
    public function userSource(AuthConfig $config, FactoryInterface $factory)
    {
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

        $session = $request->getAttribute('auth');

        if (!$session instanceof AuthContext) {
            throw new SugarException("Unable to resolve active session using active request");
        }

        return $session;
    }
}