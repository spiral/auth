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
     * Fully compiled hash code for a stored token.
     *
     * @return string
     */
    public function getValue(): string;

    /**
     * PrimaryKey of associated user instance.
     *
     * @return mixed
     */
    public function getUserPK();

    /**
     * Associated token operator.
     *
     * @return TokenOperatorInterface
     */
    public function getOperator(): TokenOperatorInterface;

    //    /**
//     * @return bool
//     */
//    public function hasExpired(): bool;

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