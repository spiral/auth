<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J), Lev Seleznev
 */

namespace Spiral\Auth\Bootloaders;

use Spiral\Auth\ContextInterface;
use Spiral\Auth\TokenManager;
use Spiral\Core\Bootloaders\Bootloader;

class AuthBootloader extends Bootloader
{
    /**
     * @var array
     */
    const BINDINGS = [
        //Authorization context
        'auth'   => ContextInterface::class,
        'tokens' => TokenManager::class
    ];
}