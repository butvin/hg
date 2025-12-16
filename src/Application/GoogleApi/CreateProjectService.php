<?php

declare(strict_types=1);

namespace Application\GoogleApi;

use Domain\GoogleApi\Model\ProjectModel;
use Domain\GoogleApi\Repository\ProjectRepositoryInterface;
use Domain\GoogleApi\ValueObject\DomainValueObject;

final readonly class CreateProjectService
{
    public function __construct(
        private ProjectRepositoryInterface $projectRepository,
    ) {
    }

    public function create(string $scheme, DomainValueObject|string $domain): ProjectModel
    {
        if ($this->projectRepository->isExistDomain($domain)) {
            throw new \DomainException(
                sprintf("Project with the domain name: %s already exists", $domain)
            );
        }

        try {
            $project = $this->projectRepository->save(new ProjectModel($scheme, $domain));
        } catch (\Exception $e) {
            throw new \RuntimeException(sprintf("Project creation failed: %s", $e->getMessage()));
        }

        return $project;
    }
}
