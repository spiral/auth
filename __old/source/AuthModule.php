<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J), Lev Seleznev
 */

namespace Spiral;

use Spiral\Auth\Configs\AuthConfig;
use Spiral\Auth\Configs\TokensConfig;
use Spiral\Auth\Configs\HashesConfig;
use Spiral\Core\DirectoriesInterface;
use Spiral\Modules\ModuleInterface;
use Spiral\Modules\PublisherInterface;
use Spiral\Modules\RegistratorInterface;

class AuthModule implements ModuleInterface
{
    /**
     * @param RegistratorInterface $registrator
     */
    public function register(RegistratorInterface $registrator)
    {
        //Exclude auth token from being encrypted
        $registrator->configure('http', 'cookies.excluded', 'spiral/auth', [
            "'auth-token',"
        ]);

        //Database based tokens
        $registrator->configure('tokenizer', 'directories', 'spiral/auth', [
            "directory('libraries') . 'spiral/auth/source/Auth/Database/',"
        ]);

        //Merge auth database with default database
        $registrator->configure('databases', 'aliases', 'spiral/auth', [
            "'auth' => 'default',"
        ]);
    }

    /**
     * @param PublisherInterface   $publisher
     * @param DirectoriesInterface $directories
     */
    public function publish(PublisherInterface $publisher, DirectoriesInterface $directories)
    {
        $publisher->publish(
            dirname(__DIR__) . '/resources/config.php',
            $directories->directory('config') . AuthConfig::CONFIG . '.php',
            PublisherInterface::FOLLOW
        );
    }
}
