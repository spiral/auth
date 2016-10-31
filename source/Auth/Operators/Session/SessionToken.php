<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */
namespace Spiral\Auth\Operators\Session;

use Spiral\Auth\Entities\AbstractToken;

class SessionToken extends AbstractToken
{
    /**
     * {@inheritdoc}
     */
    public function getHash()
    {
        return 'session';
    }

    public function isExpired()
    {
    }
}