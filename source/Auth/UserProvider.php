<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J), Lev Seleznev
 */
namespace Spiral\Auth;

class UserProvider implements UserProviderInterface
{
    /**
     * @var UserSourceInterface
     */
    private $source;

    /**
     * @param UserSourceInterface $source
     */
    public function __construct(UserSourceInterface $source)
    {
        $this->source = $source;
    }

    /**
     * @param TokenInterface $token
     * @return UserInterface
     */
    public function getUser(TokenInterface $token)
    {
        return $this->source->findByPK($token->userPK());
    }
}