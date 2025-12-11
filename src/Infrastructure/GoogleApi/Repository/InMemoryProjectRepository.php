<?php

declare(strict_types=1);

namespace Infrastructure\GoogleApi\Repository;

use Domain\GoogleApi\Model\Project;
use Domain\GoogleApi\Repository\ProjectRepositoryInterface;
use Domain\GoogleApi\ValueObject\DomainName;

class InMemoryProjectRepository implements ProjectRepositoryInterface
{
    /** @var Project[] */
    private array $items = [];

    public function getById(int $id): ?Project
    {
        return $this->items[$id] ?? null;
    }

    public function save(Project $project): Project
    {
        $this->items[] = $project;

        $id = array_search($project, $this->items, true);
        if ($id !== false) {
            $project->setId((int)$id);
        } else {
            $id = time();
            $project->setId($id);
        }

        return $project;
    }

    public function remove(Project $project): void
    {
        unset($this->items[$project->getId()]);
    }

    public function findAllActive(): array
    {
        return array_filter($this->items, static fn(Project $project): bool => $project->isActive());
    }

    public function isExistDomain(string $domain): bool
    {
        return null !== $this->getByDomain($domain);
    }

    public function getByDomain(string|DomainName $domain): ?Project
    {
        return array_find($this->items, fn(Project $project) => $project->getDomain() === $domain);

//        return array_find($this->items, function(Project $project) use ($domain) {
//            return $project->getDomain() === $domain;
//        });

//        foreach ($this->items as $project) {
//            if ($project->getDomain() === $domain) {
//                return $project;
//            }
//        }
//        return null;
    }

    public function getSitemaps(Project $project): array
    {
        return $project->getSitemaps();
    }
}
