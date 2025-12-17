<?php

declare(strict_types=1);

namespace Domain\GoogleApi\Entity;

use DateTimeInterface;
use DateTimeImmutable;

final class Sitemap
{
    private ?int $id = null;

    private readonly DateTimeInterface $createdAt;

    public function __construct(
        private readonly string $url,
    ) {
        $this->id !== null ?: $this->id = time();
        $this->createdAt = new DateTimeImmutable('now');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }
}