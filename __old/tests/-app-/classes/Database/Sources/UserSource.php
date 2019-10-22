<?php
/**
 * Spiral Framework, SpiralScout LLC.
 *
 * @package   spiralFramework
 * @author    Anton Titov (Wolfy-J)
 * @copyright ©2009-2011
 */

namespace TestApplication\Database\Sources;

use Spiral\Auth\Sources\UsernameSourceInterface;
use Spiral\ORM\Entities\RecordSource;
use TestApplication\Database\User;

class UserSource extends RecordSource implements UsernameSourceInterface
{
    const RECORD = User::class;

    public function findByUsername(string $username)
    {
        return $this->findOne(['username' => $username]);
    }
}