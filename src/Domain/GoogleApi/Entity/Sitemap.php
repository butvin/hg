<?php

declare(strict_types=1);

namespace Domain\GoogleApi\Entity;

final class Sitemap
{
    private ?int $id = null;

    private readonly \DateTimeInterface $createdAt;

    public function __construct(
        private readonly string $url,
    ) {
        null !== $this->id ?: $this->id = time();
        $this->createdAt = new \DateTimeImmutable('now');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }
}