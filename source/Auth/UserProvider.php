<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */
namespace Spiral\Auth;

class UserProvider implements UserProviderInterface
{
    /**
     * @var SourceInterface
     */
    private $source;

    /**
     * @param SourceInterface $source
     */
    public function __construct(SourceInterface $source)
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