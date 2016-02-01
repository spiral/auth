<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J), Lev Seleznev
 */
namespace Spiral\Auth\Entities;

use Spiral\Auth\TokenInterface;

class Token implements TokenInterface
{
    /**
     * @var string
     */
    private $userPK;

    /**
     * @var string
     */
    private $provider;

    /**
     * @param $userPK
     * @param $provider
     */
    public function __construct($userPK, $provider)
    {
        $this->userPK = $userPK;
        $this->provider = $provider;
    }

    /**
     * @return string
     */
    public function userPK()
    {
        return $this->userPK;
    }

    /**
     * @return string
     */
    public function getProvider()
    {
        return $this->provider;
    }
}