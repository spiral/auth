<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J), Lev Seleznev
 */

namespace Spiral\Auth;

interface TokenInterface2
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
    public function getUserID(); // todo: getPayload()

    // todo: operator?

    // todo: DO I NEED IT HERE?

    /**
     * Associated token operator.
     *
     * @return TokenOperatorInterface
     */
    public function getOperator(): TokenOperatorInterface;

    /**
     * Transfer token to another operator handler.
     *
     * @param TokenOperatorInterface $operator
     *
     * @return self
     */
    public function withOperator(TokenOperatorInterface $operator): self;
}