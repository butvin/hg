<?php

declare(strict_types=1);

namespace Domain\GoogleApi\Repository;

use Domain\GoogleApi\Entity\Project;
use Domain\GoogleApi\Entity\Sitemap;

interface ProjectRepositoryInterface
{
    public function getById(int $id): ?Project;

    public function save(Project $project): Project;

    public function remove(Project $project): void;

    /** @return Project[] */
    public function findAll(): array;

    public function isExistDomain(string $domain): bool;

    public function getByDomain(string $domain): ?Project;

    /** @return Sitemap[] */
    public function getSitemaps(Project $project): array;
}
