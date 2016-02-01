<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */
namespace Spiral\Auth;

interface UserInterface
{
    /**
     * @return string
     */
    public function primaryKey();
}