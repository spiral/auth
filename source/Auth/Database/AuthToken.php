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
use Spiral\Auth\Traits\OperatorTrait;
use Spiral\Models\Traits\TimestampsTrait;
use Spiral\ORM\RecordEntity;

class AuthToken extends RecordEntity implements TokenInterface
{
    use OperatorTrait;
    use TimestampsTrait {
        touch as private touchTimestamps;
    }

    const DATABASE = 'auth';

    const SECURED = [];

    const SCHEMA = [
        'id'           => 'bigPrimary',
        'user_pk'      => 'string(32)',
        'token_value'  => 'string(128)',
        'token_hash'   => 'string(128)',
        'expires_at'   => 'datetime',
        'count_visits' => 'int'
    ];

    const DEFAULTS = [
        'count_visits' => 0
    ];

    const INDEXES = [
        [self::UNIQUE, 'token_hash']
    ];

    public function touch()
    {
        $this->touchTimestamps();
        $this->count_visits++;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->token_value;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getExpiration(): \DateTimeInterface
    {
        return $this->expires_at instanceof \DateTimeInterface
            ? $this->expires_at
            : new \DateTime($this->expires_at);
    }

    /**
     * @return mixed
     */
    public function getUserPK()
    {
        return $this->user_pk;
    }
}