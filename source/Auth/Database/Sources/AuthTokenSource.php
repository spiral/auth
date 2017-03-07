<?php
/**
 * Spiral Framework, SpiralScout LLC.
 *
 * @package   spiralFramework
 * @author    Anton Titov (Wolfy-J)
 * @copyright ©2009-2011
 */

namespace Spiral\Auth\Database\Sources;

use Spiral\Auth\Database\AuthToken;
use Spiral\ORM\Entities\RecordSource;

class AuthTokenSource extends RecordSource
{
    const RECORD = AuthToken::class;


}