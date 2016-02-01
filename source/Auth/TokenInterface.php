<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J), Lev Seleznev
 */
namespace Spiral\Auth;

interface TokenInterface
{
    /**
     * @return string
     */
    public function getHash();

    /**
     * @return mixed
     */
    public function userPK();
}