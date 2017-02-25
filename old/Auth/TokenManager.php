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
     * @param string        $operator
     * @param UserInterface $user
     * @return TokenInterface
     */
    public function createToken($operator, UserInterface $user)
    {
        return $this->getOperator($operator)->createToken($user);
    }

    /**
     * @param Request $request
     * @param string  $name
     * @return TokenOperatorInterface|null
     */
    public function detectOperator(Request $request, &$name)
    {
        foreach ($this->config->getOperators() as $name) {
            $operator = $this->getOperator($name);

            if ($operator->hasToken($request)) {
                return $operator;
            }
        }

        return null;
    }

    /**
     * @param string $name
     * @return TokenOperatorInterface
     */
    public function getOperator($name)
    {
        return $this->factory->make(
            $this->config->operatorClass($name),
            $this->config->operatorOptions($name)
        );
    }
}