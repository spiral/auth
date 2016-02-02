<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */
namespace Spiral\Auth\ORM;

use Spiral\Auth\TokenInterface;
use Spiral\ORM\Record;

/**
 * Abstract implementation of ORM token entity.
 */
abstract class AbstractToken extends Record implements TokenInterface
{
    /**
     * Field to be used to store user primary key reference.
     */
    const USER_PRIMARY_KEY = 'user_id';

    /**
     * @var array
     */
    protected $schema = [
        'hashCode'        => 'string(128)',
        'time_expiration' => 'datetime'
    ];

    /**
     * @var array
     */
    protected $indexes = [
        [self::UNIQUE, 'hashCode'],
        [self::INDEX, 'hashCode', 'time_expiration']
    ];

    /**
     * {@inheritdoc}
     */
    public function getHash()
    {
        return $this->getField('hashCode');
    }

    /**
     * @param string $hashCode
     * @return $this
     */
    public function setHash($hashCode)
    {
        $this->setField('hashCode', $hashCode);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getUserPK()
    {
        return $this->getField(static::USER_PRIMARY_KEY);
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
}