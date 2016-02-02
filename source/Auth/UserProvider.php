<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J), Lev Seleznev
 */
namespace Spiral\Auth;

use Spiral\Auth\Configs\AuthConfig;
use Spiral\Core\FactoryInterface;

class UserProvider implements UserProviderInterface
{
    /**
     * only for User provider
     *
     * @var UserSourceInterface
     */
    private $userSource = null;

    /** @var AuthConfig */
    protected $config;

    /** @var FactoryInterface */
    protected $factory;

    /**
     * @param AuthConfig $config
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
        return $this->getUserSource()->findByPK($token->userPK());
    }

    /**
     * @return UserSourceInterface
     */
    private function getUserSource()
    {
        if (empty($this->userSource)) {
            $this->userSource = $this->factory->make($this->config->userSource());
        }

        return $this->userSource;
    }
}