<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J), Lev Seleznev
 */

namespace Spiral\Auth;

/**
 * Implement this interface to automatically link auth-tokens to your model.
 */
interface UserInterface
{
    /**
     * @return string|null
     */
    public function primaryKey();
}