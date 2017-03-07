<?php
/**
 * Spiral Framework, SpiralScout LLC.
 *
 * @package   spiralFramework
 * @author    Anton Titov (Wolfy-J)
 * @copyright ©2009-2011
 */

namespace Spiral\Auth\Operators;

use Spiral\Auth\Operators\Bridges\BridgeInterface;
use Spiral\Auth\Sources\TokenSourceInterface;
use Spiral\Auth\TokenOperatorInterface;

/**
 * Provides ability to create persistent tokens thought associated token source.
 */
class PersistentOperator implements TokenOperatorInterface
{
    /**
     * PersistentOperator constructor.
     *
     * @param TokenSourceInterface $source
     * @param int                  $lifetime
     * @param BridgeInterface      $handler
     */
    public function __construct(
        TokenSourceInterface $source,
        int $lifetime,
        BridgeInterface $handler
    ) {

    }
}