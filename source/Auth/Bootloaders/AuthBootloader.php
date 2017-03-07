<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J), Lev Seleznev
 */

namespace Spiral\Auth\Bootloaders;

use Spiral\Auth\ContextInterface;
use Spiral\Auth\Sources\CredentialsSourceInterface;
use Spiral\Auth\Sources\UserSourceInterface;
use Spiral\Auth\TokenManager;
use Spiral\Core\Bootloaders\Bootloader;

class AuthBootloader extends Bootloader
{
    /**
     * @var array
     */
    const BINDINGS = [
        //Authorization context
        'auth'                     => ContextInterface::class,
        'tokens'                   => TokenManager::class,

        //By Default we expect UserSource to be the same as Username based source
        UserSourceInterface::class => CredentialsSourceInterface::class
    ];
}