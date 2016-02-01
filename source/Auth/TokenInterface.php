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
     * Specify token provider name.
     *
     * @param string $name
     * @return self
     */
    public function withName($name);

    /**
     * @return string
     */
    public function getName();

    /**
     * @return mixed
     */
    public function userPK();
}