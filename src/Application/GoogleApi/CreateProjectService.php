<?php

declare(strict_types=1);

namespace Application\GoogleApi;

use Domain\GoogleApi\Model\Project;
use Domain\GoogleApi\Repository\ProjectRepositoryInterface;
use Domain\GoogleApi\ValueObject\DomainName;
use DomainException;
use RuntimeException;

final readonly class CreateProjectService
{
    public function __construct(
        private ProjectRepositoryInterface $projectRepository,
    ) {
    }

    public function create(string $scheme, DomainName|string $domain): Project
    {
        if ($this->projectRepository->isExistDomain($domain)) {
            throw new DomainException(sprintf("Project with the domain name: %s already exists", $domain));
        }

        try {
            $project = $this->projectRepository->save(
                new Project($scheme, $domain)
            );
        } catch (\Exception $e) {
            throw new RuntimeException(sprintf("Project creation failed: %s", $e->getMessage()));
        }

        return $project;
    }
}
