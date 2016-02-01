<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J), Lev Seleznev
 */
namespace Spiral\Auth\Sources;

use Spiral\Auth\SourceInterface;
use Spiral\Auth\PasswordAwareInterface;

interface CredentialsInterface extends SourceInterface
{
    /**
     * @param string $username
     * @return PasswordAwareInterface|null
     */
    public function findByUsername($username);
}