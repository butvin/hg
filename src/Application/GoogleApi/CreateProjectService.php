<?php

declare(strict_types=1);

namespace Application\GoogleApi;

use Application\GoogleApi\Dto\CreateProjectRequestDto;
use Domain\GoogleApi\Model\ProjectModel;
use Domain\GoogleApi\Repository\ProjectRepositoryInterface;
use Domain\GoogleApi\ValueObject\DomainValueObject;
use Throwable;
use DomainException;
use RuntimeException;

final readonly class CreateProjectService
{
    public function __construct(
        private ProjectRepositoryInterface $projectRepository,
    ) {
    }

    public function create(CreateProjectRequestDto $dto): ProjectModel
    {
        if ($this->projectRepository->isExistDomain($dto->getDomain())) {
            throw new DomainException(
                sprintf("Project with the domain name: %s already exists", $dto->getDomain())
            );
        }

        try {
            $project = $this->projectRepository->save(
                new ProjectModel(DomainValueObject::fromString($dto->getDomain()), $dto->getScheme())
            );
        } catch (Throwable $e) {
            throw new RuntimeException(sprintf("Project creation failed: %s", $e->getMessage()));
        }

        return $project;
    }
}
