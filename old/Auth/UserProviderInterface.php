<?php

namespace Spiral\Auth;

interface UserProviderInterface
{
    /**
     * @param TokenInterface $token
     * @return UserInterface
     */
    public function getUser(TokenInterface $token);
}