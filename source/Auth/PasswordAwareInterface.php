<?php
/**
 * Spiral Framework, SpiralScout LLC.
 *
 * @package   spiralFramework
 * @author    Anton Titov (Wolfy-J)
 * @copyright ©2009-2011
 */

namespace Spiral\Auth;

/**
 * Declares ability to provide hashed password value.
 */
interface PasswordAwareInterface extends UserInterface
{
    /**
     * @return string
     */
    public function getPasswordHash(): string;
}