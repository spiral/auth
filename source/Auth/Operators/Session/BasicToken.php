<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */
namespace Spiral\Auth\Operators\Session;

use Spiral\Auth\Entities\AbstractToken;

class BasicToken extends AbstractToken
{
    /**
     * {@inheritdoc}
     */
    public function getHash()
    {
        return 'basic-http';
    }

    public function isExpired()
    {
    }
}