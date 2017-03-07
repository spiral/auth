<?php
/**
 * Spiral Framework, SpiralScout LLC.
 *
 * @package   spiralFramework
 * @author    Anton Titov (Wolfy-J)
 * @copyright ©2009-2011
 */

namespace Spiral\Auth\Database;

use Spiral\Auth\TokenInterface;
use Spiral\Auth\TokenOperatorInterface;
use Spiral\Models\Traits\TimestampsTrait;
use Spiral\ORM\RecordEntity;

class AuthToken extends RecordEntity implements TokenInterface
{
    use TimestampsTrait;

    const DATABASE = 'auth';

    /**
     * @var TokenOperatorInterface
     */
    private $operator;

    public function getValue(): string
    {
        // TODO: Implement getValue() method.
    }

    public function getUserPK()
    {
        // TODO: Implement getUserPK() method.
    }

    /**
     * {@inheritdoc}
     */
    public function withOperator(TokenOperatorInterface $operator): self
    {
        $token = clone $this;
        $token->operator = $operator;

        return $token;
    }

    /**
     * {@inheritdoc}
     */
    public function getOperator(): TokenOperatorInterface
    {
        return $this->operator;
    }
}