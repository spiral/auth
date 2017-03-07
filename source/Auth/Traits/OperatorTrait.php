<?php
/**
 * Spiral Framework, SpiralScout LLC.
 *
 * @package   spiralFramework
 * @author    Anton Titov (Wolfy-J)
 * @copyright ©2009-2011
 */

namespace Spiral\Auth\Traits;

use Spiral\Auth\TokenInterface;
use Spiral\Auth\TokenOperatorInterface;

/**
 * Provides ability to carry reference to associated operator.
 */
trait OperatorTrait
{
    /**
     * @var TokenOperatorInterface
     */
    private $operator;

    /**
     * {@inheritdoc}
     */
    public function getOperator(): TokenOperatorInterface
    {
        return $this->operator;
    }

    /**
     * {@inheritdoc}
     */
    public function withOperator(TokenOperatorInterface $operator): TokenInterface
    {
        $token = clone $this;
        $token->operator = $operator;

        return $token;
    }
}