<?php

declare(strict_types=1);

namespace UI\Http\Controller;

use Application\GoogleApi\CreateProjectService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

readonly class GoogleApiController
{
    public function __construct(
        private CreateProjectService $service
    ) {
    }

    public function create(Request $request): JsonResponse
    {
        $project = $this->service->create('https', 'example.com');

        return new JsonResponse([
            'message' => sprintf(
                "Project#%s %s://%s created successfully at %s",
                $project->getId(),
                $project->getScheme(),
                $project->getDomain(),
                $project->getCreatedAt()->format('Y-m-d H:i:s')
            ),
        ], Response::HTTP_CREATED);
    }

}
