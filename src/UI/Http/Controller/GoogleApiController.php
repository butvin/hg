<?php

declare(strict_types=1);

namespace UI\Http\Controller;

use Application\GoogleApi\CreateProjectService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class GoogleApiController
{
    public function __construct(
        private readonly CreateProjectService $service
    ) {
    }

    public function index(): JsonResponse
    {
        return new JsonResponse(['message' => self::class], Response::HTTP_OK);
    }

    public function create(): JsonResponse
    {
        $project = $this->service->create('https', 'example.com');

        return new JsonResponse([
            'message' => sprintf(
                "Project %s created successfully at %s",
                $project->getDomain(),
                $project->getCreatedAt()->format('Y-m-d H:i:s')
            ),
        ], Response::HTTP_CREATED);
    }

}
