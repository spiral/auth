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
use Spiral\Auth\Exceptions\LogicException;
use Spiral\Auth\Sources\TokenSourceInterface;
use Spiral\Auth\TokenInterface;
use Spiral\Auth\UserInterface;
use Spiral\ORM\Entities\RecordSource;
use Spiral\Support\Strings;

class AuthTokenSource extends RecordSource implements TokenSourceInterface
{
    const RECORD = AuthToken::class;

    /**
     * {@inheritdoc}
     */
    public function findToken(string $token)
    {
        /**
         * @var AuthToken $token
         */
        $token = $this->findOne([
            'token_hash' => hash('sha512', $token)
        ]);

        if (!empty($token) && $token->getExpiration() < new \DateTime('now')) {
            //Token has expired
            return null;
        }

        return $token;
    }

    /**
     * {@inheritdoc}
     */
    public function createToken(UserInterface $user, int $lifetime): TokenInterface
    {
        //Base64 encoded random token value
        $tokenValue = Strings::random(128);

        /** @var AuthToken $token */
        $token = $this->create([
            'user_pk'     => $user->primaryKey(),
            'token_value' => $tokenValue,
            'token_hash'  => hash('sha512', $tokenValue),
            'expires_at'  => (new \DateTime('now'))->add(
                new \DateInterval("PT{$lifetime}S")
            )
        ]);

        $token->save();

        return $token;
    }

    /**
     * {@inheritdoc}
     */
    public function touchToken(TokenInterface $token, int $lifetime): bool
    {
        if (!$token instanceof AuthToken) {
            throw new LogicException("Invalid token type, instances of AuthToken are expected");
        }

        $token->touch();
        $token->save();

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteToken(TokenInterface $token)
    {
        if (!$token instanceof AuthToken) {
            throw new LogicException("Invalid token type, instances of AuthToken are expected");
        }

        $token->delete();
    }
}