<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J), Lev Seleznev
 */
namespace Spiral\Auth\Entities;

use Spiral\Auth\TokenInterface;

abstract class AbstractToken implements TokenInterface
{
    /**
     * @var mixed
     */
    private $userPK;

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
    public function userPK()
    {
        return $this->userPK;
    }
}