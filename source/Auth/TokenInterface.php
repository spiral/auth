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
     * Char to join/explode token partials. Internal.
     */
    const DELIMITER = '.';

    /**
     * Fully compiled hash code for a stored token.
     *
     * @return string
     */
    public function getHash(): string;

    /**
     * PrimaryKey of associated user instance.
     *
     * @return string|mixed
     */
    public function getUserPK(): string;

    /**
     * @return bool
     */
    public function hasExpired(): bool;

    /**
     * Name of operator which created this token.
     *
     * @return string
     */
  //  public function getOperator(): string;

    /**
     * Transfer token to another operator handler.
     *
     * @param string $operator
     * @return self
     */
   // public function withOperator($operator): self;

    /**
     * @return string
     */
   // public function getSource(): string;

    /**
     * //??
     * @param string $source
     */
   // public function setSource($source): string;
}