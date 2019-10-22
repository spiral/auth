<?php
/**
 * Spiral Framework, SpiralScout LLC.
 *
 * @package   spiralFramework
 * @author    Anton Titov (Wolfy-J)
 * @copyright ©2009-2011
 */

namespace TestApplication\Database;

use Spiral\Auth\PasswordAwareInterface;
use Spiral\ORM\Record;

class User extends Record implements PasswordAwareInterface
{
    const SCHEMA = [
        'id'            => 'primary',
        'username'      => 'string',
        'password_hash' => 'string'
    ];

    public function getPasswordHash(): string
    {
        return $this->password_hash;
    }
}