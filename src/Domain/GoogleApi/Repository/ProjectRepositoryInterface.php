<?php

declare(strict_types=1);

namespace Domain\GoogleApi\Repository;

use Domain\GoogleApi\Model\Project;
use Domain\GoogleApi\Model\Sitemap;

interface ProjectRepositoryInterface
{
    public function getById(int $id): ?Project;

    public function save(Project $project): Project;

    public function remove(Project $project): void;

    /** @return Project[] */
    public function findAllActive(): array;

    public function isExistDomain(string $domain): bool;

    public function getByDomain(string $domain): ?Project;

    /** @return Sitemap[] */
    public function getSitemaps(Project $project): array;
}
