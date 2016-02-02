<?php

namespace Spiral\Auth\Entities;

use Spiral\Auth\TokenInterface;
use Spiral\Models\Traits\TimestampsTrait;
use Spiral\ORM\Record;

abstract class AbstractPersistenceToken extends Record implements TokenInterface
{
    use TimestampsTrait;

    /**
     * @var array
     */
    protected $schema = [
        'id' => 'primary',
        'hash' => 'string(255)',
    ];

    public function getHash()
    {
        return $this->hash;
    }

    abstract public function userPK();
}