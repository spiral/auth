<?php
/**
 * Spiral Framework, SpiralScout LLC.
 *
 * @package   spiralFramework
 * @author    Anton Titov (Wolfy-J)
 * @copyright ©2009-2011
 */

namespace TestApplication\Bootloaders;

use Spiral\Core\Bootloaders\Bootloader;
use Spiral\Http\HttpDispatcher;
use Spiral\Http\Routing\ControllersRoute;

class HttpBootloader extends Bootloader
{
    /**
     * Requested to be bootloaded.
     */
    const BOOT = true;

    /**
     * @param HttpDispatcher $http
     */
    public function boot(HttpDispatcher $http)
    {
        $http->defaultRoute($this->defaultRoute());
    }

    /**
     * @return ControllersRoute
     */
    private function defaultRoute(): ControllersRoute
    {
        $defaultRoute = new ControllersRoute(
            'default',
            '[<controller>[/<action>[/<id>]]]',
            'TestApplication\Controllers'
        );

        return $defaultRoute->withDefaults([
            'controller' => 'default',
        ]);
    }
}
