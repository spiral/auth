<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J), Lev Seleznev
 */
namespace Spiral\Auth;

use Psr\Http\Message\ServerRequestInterface as Request;
use Spiral\Auth\Configs\AuthConfig;
use Spiral\Core\FactoryInterface;

class TokenManager
{
    /**
     * @var AuthConfig
     */
    private $config;

    /**
     * @var FactoryInterface
     */
    private $factory;

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
     * @param Request $request
     * @return string
     */
    public function detectProvider(Request $request)
    {
        foreach ($this->config->getProviders() as $name) {
            $provider = $this->getProvider($name);

            if ($provider->hasToken($request)) {
                return $name;
            }
        }

        return null;
    }

    /**
     * @param string        $provider
     * @param UserInterface $user
     * @return TokenInterface
     */
    public function createToken($provider, UserInterface $user)
    {
        return $this->getProvider($provider)->createToken($user);
    }

    /**
     * @param string $name
     * @return ProviderInterface
     */
    public function getProvider($name)
    {
        return $this->factory->make(
            $this->config->providerClass($name),
            $this->config->providerOptions($name)
        );
    }
}