<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J), Lev Seleznev
 */
namespace Spiral\Auth;

use Spiral\Auth\Configs\AuthConfig;
use Spiral\Auth\Sources\UserSourceInterface;
use Spiral\Core\FactoryInterface;

class UserProvider implements UserProviderInterface
{
    /**
     * @var UserSourceInterface
     */
    private $userSource = null;

    /**
     * @var AuthConfig
     */
    protected $config;

    /**
     * @var FactoryInterface
     */
    protected $factory;

    /**
     * @param AuthConfig       $config
     * @param FactoryInterface $factory
     */
    public function __construct(AuthConfig $config, FactoryInterface $factory)
    {
        $this->config = $config;
        $this->factory = $factory;
    }

    /**
     * @param TokenInterface $token
     * @return UserInterface
     */
    public function getUser(TokenInterface $token)
    {
        return $this->userSource()->findByPK($token->getUserPK());
    }

    /**
     * @return UserSourceInterface
     */
    private function userSource()
    {
        if (empty($this->userSource)) {
            //Lazy loading
            $this->userSource = $this->factory->make(
                $this->config->userSource()
            );
        }

        return $this->userSource;
    }
}