<?php
/**
 * Spiral Framework, SpiralScout LLC.
 *
 * @package   spiralFramework
 * @author    Anton Titov (Wolfy-J)
 * @copyright ©2009-2011
 */

namespace Spiral\Auth;

use Spiral\Auth\Traits\OperatorTrait;

/**
 * Authorization token with immutable token value and operator association. Operator association
 * can be completed only on construction.
 */
final class AuthToken implements TokenInterface, \JsonSerializable
{
    use OperatorTrait;

    /**
     * @var string
     */
    private $value;

    /**
     * @var mixed
     */
    private $userPK;

    /**
     * @param string                 $value
     * @param mixed                  $userPK
     * @param TokenOperatorInterface $operator
     */
    public function __construct(string $value, $userPK, TokenOperatorInterface $operator)
    {
        $this->value = $value;
        $this->userPK = $userPK;
        $this->operator = $operator;
    }

    /**
     * {@inheritdoc}
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     */
    public function getUserPK()
    {
        return $this->userPK;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getValue();
    }

    /**
     * @return string
     */
    public function jsonSerialize()
    {
        return $this->getValue();
    }
}