<?php
/**
 * Spiral Framework, SpiralScout LLC.
 *
 * @package   spiralFramework
 * @author    Anton Titov (Wolfy-J)
 * @copyright ©2009-2011
 */

namespace Spiral\Auth\Operators;

use Spiral\Auth\Sources\TokenSourceInterface;
use Spiral\Auth\TokenOperatorInterface;

/**
 * Manages tokens thought database.
 */
class DatabaseOperator implements TokenOperatorInterface
{
    public function __construct(
        TokenSourceInterface $source

    )
    {
    }
}