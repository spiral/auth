<?php

/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

declare(strict_types=1);

namespace Spiral\App\Controller;

use Spiral\Auth\AuthContextInterface;
use Spiral\Auth\TokenStorageInterface;
use Spiral\Core\Exception\ControllerException;
use Spiral\Security\GuardInterface;

class AuthController
{
    public function do(GuardInterface $guard)
    {
        if (!$guard->allows('do')) {
            throw new ControllerException("Unauthorized permission 'do'", ControllerException::FORBIDDEN);
        }

        return 'ok';
    }

    public function token(AuthContextInterface $authContext)
    {
        if ($authContext->getToken() !== null) {
            return $authContext->getToken()->getID();
        }

        return 'none';
    }

    public function login(AuthContextInterface $authContext, TokenStorageInterface $tokenStorage)
    {
        $authContext->start(
            $tokenStorage->create(['userID' => 1])
        );

        return 'OK';
    }
}