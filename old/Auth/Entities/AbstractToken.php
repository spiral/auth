<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J), Lev Seleznev
 */
namespace Spiral\Auth\Entities;

use Spiral\Auth\TokenInterface;

abstract class AbstractToken implements TokenInterface, \JsonSerializable
{
    /**
     * @var mixed
     */
    private $userPK;

    /**
     * Operator which has created this token.
     *
     * @var string
     */
    protected $operator;

    /**
     * Source from which token was fetched.
     *
     * @var string
     */
    protected $source;

    /**
     * @param mixed $userPK
     */
    public function __construct($userPK)
    {
        $this->userPK = $userPK;
    }

    /**
     * @return mixed
     */
    public function getUserPK()
    {
        return $this->userPK;
    }

    /**
     * @return string
     */
    public function jsonSerialize()
    {
        return $this->getHash();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getHash();
    }

    public function isExpired()
    {
    }

    /**
     * @return string
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * @param string $operator
     */
    public function setOperator($operator)
    {
        $this->operator = $operator;
    }

    /**
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param string $source
     */
    public function setSource($source)
    {
        $this->source = $source;
    }
}