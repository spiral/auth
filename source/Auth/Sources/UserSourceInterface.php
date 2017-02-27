<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J), Lev Seleznev
 */

namespace Spiral\Auth\Sources;

use Spiral\Auth\UserInterface;

interface UserSourceInterface
{
    /**
     * @param mixed $id
     * @return UserInterface|null
     */
    public function findByPK($id);
}