<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J), Lev Seleznev
 */
namespace Spiral\Core\Traits;

use Interop\Container\ContainerInterface as InteropContainer;
use Spiral\Core\Exceptions\Container\AutowireException;
use Spiral\Core\Exceptions\SugarException;

/**
 * Twin original SharedTrait, include it and your component will get access to all shared bindings
 * of your application.
 *
 * Here you list your own virtual bindings to help your IDE:
 *
 * @see AuthBootloader
 *
 * Application bindings:
 *
 * @property-read \Spiral\Auth\ContextInterface $auth
 */
trait SharedTrait
{
    /**
     * Shortcut to Container get method.
     *
     * @see ContainerInterface::get()
     * @param string $alias
     * @return mixed|null|object
     * @throws AutowireException
     * @throws SugarException
     */
    public function __get($alias)
    {
        if ($this->container()->has($alias)) {
            return $this->container()->get($alias);
        }

        throw new SugarException("Unable to get property binding '{$alias}'.");

        //no parent call, too dangerous
    }

    /**
     * @return InteropContainer
     */
    abstract protected function container();
}
