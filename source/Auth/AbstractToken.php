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
     * @var string
     */
    private $name;

    /**
     * @param mixed $userPK
     */
    public function __construct($userPK)
    {
        $this->userPK = $userPK;
    }

    /**
     * @param string $name
     * @return self
     */
    public function withName($name)
    {
        $token = clone $this;
        $token->name = $name;

        return $token;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function userPK()
    {
        return $this->userPK;
    }

    /**
     * @return null|string
     */
    public function jsonSerialize()
    {
        return $this->getHash();
    }

    /**
     * @return null|string
     */
    public function __toString()
    {
        return $this->getHash();
    }
}