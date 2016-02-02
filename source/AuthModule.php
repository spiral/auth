<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J), Lev Seleznev
 */
namespace Spiral;

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
        $registrator->configure('http', 'cookies.exclude', 'spiral/auth', [
            "//Authorization cookie",
            "'auth-token'"
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
            $directories->directory('config') . 'modules/auth.php',
            PublisherInterface::FOLLOW
        );
    }
}
