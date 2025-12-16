<?php

declare(strict_types=1);

namespace Domain\GoogleApi\Repository;

use Domain\GoogleApi\Model\ProjectModel;
use Domain\GoogleApi\Model\SitemapModel;

interface ProjectRepositoryInterface
{
    public function getById(int $id): ?ProjectModel;

    public function save(ProjectModel $project): ProjectModel;

    public function remove(ProjectModel $project): void;

    /** @return ProjectModel[] */
    public function findAll(): array;

    public function isExistDomain(string $domain): bool;

    public function getByDomain(string $domain): ?ProjectModel;

    /** @return SitemapModel[] */
    public function getSitemaps(ProjectModel $project): array;
}
