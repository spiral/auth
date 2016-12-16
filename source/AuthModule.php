<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J), Lev Seleznev
 */
namespace Spiral;

use Spiral\Auth\Configs\AuthConfig;
use Spiral\Auth\Configs\GeneratorConfig;
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
        $registrator->configure('http', 'cookies.excluded', 'spiral/auth', [
            "//Authorization cookie",
            "'auth-token'"
        ]);

        //Models
        $registrator->configure('tokenizer', 'directories', 'spiral/auth', [
            "directory('libraries') . 'spiral/auth'"
        ]);
    }

    /**
     * @param PublisherInterface   $publisher
     * @param DirectoriesInterface $directories
     */
    public function publish(PublisherInterface $publisher, DirectoriesInterface $directories)
    {
        $publisher->publish(
            __DIR__ . '/config/auth.php',
            $directories->directory('config') . AuthConfig::CONFIG . '.php',
            PublisherInterface::FOLLOW
        );

        $publisher->publish(
            __DIR__ . '/config/hashes.php',
            $directories->directory('config') . HashesConfig::CONFIG . '.php',
            PublisherInterface::FOLLOW
        );

        $publisher->publish(
            __DIR__ . '/config/generator.php',
            $directories->directory('config') . GeneratorConfig::CONFIG . '.php',
            PublisherInterface::FOLLOW
        );
    }
}
