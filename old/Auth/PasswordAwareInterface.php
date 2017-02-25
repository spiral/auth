<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J), Lev Seleznev
 */
namespace Spiral\Auth;

/**
 * User which knows about it's password hash.
 */
interface PasswordAwareInterface extends UserInterface
{
    /**
     * @return string
     */
    public function getPasswordHash();
}