<?php

declare(strict_types=1);

namespace Domain\GoogleApi\Entity;

use Domain\GoogleApi\ValueObject\DomainValueObject;
use DateTimeInterface;
use DateTimeImmutable;

final class Project
{
    private ?int $id = null;
    private readonly string $scheme;
    private readonly DomainValueObject $domain;

    /** @var Sitemap[] */
    private array $sitemaps = [];
    private readonly DateTimeInterface $createdAt;

    public function __toString(): string
    {
        return sprintf("%s(%s://%s)", self::class, $this->scheme, $this->domain);
    }

    public function __construct(
        DomainValueObject $domain,
        ?string $scheme = null,
    ) {
        if (null === $scheme) {
            $this->scheme = 'https';
        }
        $this->domain = $domain;
        $this->createdAt = new DateTimeImmutable();
    }

    public function addSitemap(Sitemap $sitemap): void
    {
        $this->sitemaps[] = $sitemap;
    }

    /** @return Sitemap[] */
    public function getSitemaps(): array
    {
        return $this->sitemaps;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getScheme(): string
    {
        return $this->scheme;
    }

    public function getDomain(): DomainValueObject
    {
        return $this->domain;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }
}
