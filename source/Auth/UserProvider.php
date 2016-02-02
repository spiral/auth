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
    private $source;

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
     * @return UserSourceInterface
     */
    protected function getSource()
    {
        if (empty($this->source)) {
            $this->source = $this->factory->make($this->config->userSource());
        }

        return $this->source;
    }

    /**
     * @param TokenInterface $token
     * @return UserInterface
     */
    public function getUser(TokenInterface $token)
    {
        return $this->getSource()->findByPK($token->userPK());
    }
}