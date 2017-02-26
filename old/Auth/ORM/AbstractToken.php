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
     * Operator which has created this token.
     *
     * @var string
     */
    protected $operator;

    /**
     * Source from which token was fetched.
     *
     * @var string
     */
    protected $source;

    /**
     * @var array
     */
    protected $schema = [
        'selector'        => 'string(128)',
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

    /**
     * {@inheritdoc}
     */
    public function getValue()
    {
        $code = $this->getSource();
        $selector = $this->getSelector();

        return join(static::DELIMITER, [$selector, $code]);
    }

    /**
     * {@inheritdoc}
     */
    public function getUserPK()
    {
        return $this->getField(static::USER_PRIMARY_KEY);
    }

    /**
     * Get selector field value.
     *
     * @return mixed|null|\Spiral\Models\AccessorInterface
     */
    public function getSelector()
    {
        return $this->getField('selector');
    }

    /**
     * Get selector field value.
     *
     * @return mixed|null|\Spiral\Models\AccessorInterface
     */
    public function getCode()
    {
        return $this->getField('code');
    }

    /**
     * Get hash field value.
     *
     * @return mixed|null|\Spiral\Models\AccessorInterface
     */
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
        return $this->getValue();
    }

    /**
     * @return bool
     */
    public function hasExpired()
    {
        return $this->time_expiration <= new \DateTime('now');
    }

    /**
     * @return string
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * @param string $operator
     */
    public function setOperator($operator)
    {
        $this->operator = $operator;
    }

    /**
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param string $source
     */
    public function setSource($source)
    {
        $this->source = $source;
    }
}