<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */
declare(strict_types=1);

namespace Spiral\Auth;

interface AuthScopeInterface
{
    public function start(TokenInterface $token, string $transport = null): void;

    public function getToken(): ?TokenInterface;

    public function getTransport(): ?string;

    public function getActor(): ?object;

    public function close(): void;

    public function isClosed(): bool;
}