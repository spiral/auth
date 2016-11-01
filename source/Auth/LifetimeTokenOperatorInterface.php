<?php
/**
 * Created by PhpStorm.
 * User: Valentin
 * Date: 28.10.2016
 * Time: 20:58
 */

namespace Spiral\Auth;


interface LifetimeTokenOperatorInterface
{
    /**
     * @return int
     */
    public function getLifetime();
}