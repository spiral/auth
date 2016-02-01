<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J), Lev Seleznev
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