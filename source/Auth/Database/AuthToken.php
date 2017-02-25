<?php
/**
 * Spiral Framework, SpiralScout LLC.
 *
 * @package   spiralFramework
 * @author    Anton Titov (Wolfy-J)
 * @copyright ©2009-2011
 */
namespace Spiral\Auth\Database;

use Spiral\Models\Traits\TimestampsTrait;
use Spiral\ORM\RecordEntity;

class AuthToken extends RecordEntity
{
    use TimestampsTrait;


}