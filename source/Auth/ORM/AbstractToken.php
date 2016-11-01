<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */
namespace Spiral\Auth\ORM;

use Spiral\Auth\TokenInterface;
use Spiral\Models\Traits\TimestampsTrait;
use Spiral\ORM\Record;

/**
 * Abstract implementation of ORM token entity.
 *
 * @property string $selector
 * @property string $series
 * @property string $hash
 * @property        $time_expiration
 */
abstract class AbstractToken extends Record implements TokenInterface
{
    use TimestampsTrait;

    /**
     * Field to be used to store user primary key reference.
     */
    const USER_PRIMARY_KEY = 'user_id';

    /**
     * @var array
     */
    protected $schema = [
        'id'              => 'primary',
        'selector'        => 'string(128)',
        'series'          => 'string(128)',
        'hash'            => 'string(128)',
        'time_expiration' => 'datetime'
    ];

    /**
     * @var array
     */
    protected $indexes = [
        [self::UNIQUE, 'hash', 'selector'],
        [self::INDEX, 'hash', 'selector', 'time_expiration']
    ];

    public $tokenCode;

    /**
     * {@inheritdoc}
     */
    public function getHash()
    {
        $hash = $this->tokenCode;
        $selector = $this->getSelector();
        $series = $this->getSeries();

        return join(static::DELIMITER, [$selector, $series, $hash]);
    }

    /**
     * {@inheritdoc}
     */
    public function getUserPK()
    {
        return $this->getField(static::USER_PRIMARY_KEY);
    }

    public function getSeries()
    {
        return $this->getField('series');
    }

    public function getSelector()
    {
        return $this->getField('selector');
    }

    public function getHashValue()
    {
        return $this->getField('hash');
    }

    /**
     * @param mixed $primaryKey
     * @return $this
     */
    public function setUserPK($primaryKey)
    {
        $this->setField(static::USER_PRIMARY_KEY, $primaryKey);

        return $this;
    }

    /**
     * @param \DateTime $expiration
     * @return $this
     */
    public function setExpiration(\DateTime $expiration)
    {
        $this->setField('time_expiration', $expiration);

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getHash();
    }

    /**
     * @return bool
     */
    public function isExpired()
    {
        return $this->time_expiration <= new \DateTime('now');
    }
}