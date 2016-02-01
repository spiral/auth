<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */
namespace Spiral\Auth;

interface SourceInterface
{
    /**
     * @param string $id
     * @return UserInterface|null
     */
    public function findByPK($id);
}