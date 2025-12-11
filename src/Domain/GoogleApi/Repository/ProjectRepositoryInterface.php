<?php

namespace Domain\GoogleApi\Repository;

use Domain\GoogleApi\Model\Project;
use Domain\GoogleApi\Model\Sitemap;

interface ProjectRepositoryInterface
{
    public function getById(int $id): ?Project;

    public function save(Project $project): void;

    public function remove(Project $project): void;

    /** @return Project[] */
    public function findAllActive(): array;

    public function isExistDomain(string $domain): bool;

    public function getByDomain(string $domain): ?Project;

//    public function getSitemapCount(Project $project): int;

    /** @return Sitemap[] */
//    public function getSitemaps(Project $project): array;
}
