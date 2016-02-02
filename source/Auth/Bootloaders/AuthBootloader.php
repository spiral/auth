<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J), Lev Seleznev
 */
namespace Spiral\Auth\Bootloaders;

use Spiral\Auth\Configs\AuthConfig;
use Spiral\Auth\ContextInterface;
use Spiral\Auth\Exceptions\AuthException;
use Spiral\Auth\Sources\UsernameSourceInterface;
use Spiral\Auth\Sources\UserSourceInterface;
use Spiral\Auth\UserProvider;
use Spiral\Auth\UserProviderInterface;
use Spiral\Core\Bootloaders\Bootloader;
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
        UsernameSourceInterface::class => UserSourceInterface::class,

        //Automatically resolved thought configuration
        UserSourceInterface::class     => [self::class, 'userSource'],

        //Shortcut
        'auth'                         => ContextInterface::class
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
}