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
     * Char to join/explode token partials.
     */
    const DELIMITER = '.';

    /**
     * Fully compiled hash code for a stored token.
     *
     * @return string
     */
    public function getHash();

    /**
     * @return mixed
     */
    public function getUserPK();

    /**
     * @return bool
     */
    public function isExpired();

    /**
     * @return string
     */
    public function getOperator();

    /**
     * @param string $operator
     */
    public function setOperator($operator);

    /**
     * @return string
     */
    public function getSource();

    /**
     * @param string $source
     */
    public function setSource($source);
}