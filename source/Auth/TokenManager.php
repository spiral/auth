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
use Spiral\Auth\Exceptions\AuthException;
use Spiral\Core\Container\SingletonInterface;
use Spiral\Core\FactoryInterface;

/**
 * Provides ability to create and store authorization tokens using set of token operators.
 */
class TokenManager implements SingletonInterface
{
    /**
     * @var AuthConfig
     */
    private $config;

    /**
     * @var TokenOperatorInterface[]
     */
    private $operators = [];

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
     * @param string        $operator
     * @param UserInterface $user
     *
     * @return TokenInterface
     */
    public function createToken(string $operator, UserInterface $user): TokenInterface
    {
        return $this->getOperator($operator)->createToken($user);
    }

    /**
     * Fetch authorization token from request if any.
     *
     * @param Request $request
     *
     * @return TokenInterface|null
     */
    public function fetchToken(Request $request)
    {
        foreach ($this->config->getOperators() as $name) {
            $operator = $this->getOperator($name);

            if ($operator->hasToken($request)) {
                return $operator->fetchToken($request);
            }
        }

        return null;
    }

    /**
     * @param string $name
     *
     * @return TokenOperatorInterface
     */
    protected function getOperator(string $name): TokenOperatorInterface
    {
        if (isset($this->operators[$name])) {
            return $this->operators[$name];
        }

        if (!$this->config->hasOperator($name)) {
            throw new AuthException("Undefined token operator '{$name}'");
        }

        return $this->operators[$name] = $this->factory->make(
            $this->config->operatorClass($name),
            $this->config->operatorOptions($name)
        );
    }
}