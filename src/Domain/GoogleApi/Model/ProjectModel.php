<?php

declare(strict_types=1);

namespace Domain\GoogleApi\Model;

use Domain\GoogleApi\ValueObject\DomainValueObject;
use DateTimeInterface;
use DateTimeImmutable;

final class ProjectModel
{
    private ?int $id = null;

    /** @var SitemapModel[] */
    private array $sitemaps = [];

    private ?string $createdBy = null;

    private ?bool $active  = null;

    private readonly DateTimeInterface $createdAt;

    public function __toString(): string
    {
        return sprintf("%s://%s", $this->scheme, $this->domain);
    }

    public function __construct(
        private readonly string $scheme,
        private readonly DomainValueObject $domain,
    ) {
        $this->createdAt = new DateTimeImmutable('now');
        if (null === $this->active) {
            $this->active = false;
        }
    }

    public function addSitemap(SitemapModel $sitemap): void
    {
        $this->sitemaps[] = $sitemap;
    }

    /** @return SitemapModel[] */
    public function getSitemaps(): array
    {
        return $this->sitemaps;
    }

    public function setActive(bool $active): void
    {
        $this->active = $active;
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

    public function getCreatedBy(): string|null
    {
        return $this->createdBy;
    }

    public function isActive(): bool
    {
        return $this->active;
    }
}
