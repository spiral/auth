<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J), Lev Seleznev
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