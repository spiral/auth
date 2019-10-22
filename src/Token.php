<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */
declare(strict_types=1);

namespace Spiral\Auth;

final class Token implements TokenInterface, \JsonSerializable
{
    /** @var string */
    private $id;

    /** @var \DateTimeInterface|null */
    private $expiresAt;

    /** @var array */
    private $payload;

    /**
     * @param string             $id
     * @param array              $payload
     * @param \DateTimeInterface $expiresAt
     */
    public function __construct(string $id, array $payload, \DateTimeInterface $expiresAt)
    {
        $this->id = $id;
        $this->payload = $payload;
        $this->expiresAt = $expiresAt;
    }

    /**
     * {@inheritdoc}
     */
    public function getID(): string
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getExpiresAt(): ?\DateTimeInterface
    {
        return $this->expiresAt;
    }

    /**
     * {@inheritdoc}
     */
    public function getPayload(): array
    {
        return $this->payload;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getID();
    }

    /**
     * @return string
     */
    public function jsonSerialize()
    {
        return $this->getID();
    }
}