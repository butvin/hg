<?php

declare(strict_types=1);

namespace UI\Http\Controller;

use Application\GoogleApi\CreateProjectService;
use Application\GoogleApi\Dto\CreateProjectRequestDto;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

readonly class GoogleApiController
{
    public function __construct(
        private CreateProjectService $service
    ) {
    }

    public function create(Request $request): JsonResponse
    {
        $domain = $request->request->get('domain');
        $scheme = $request->request->get('scheme');

        if (!$domain) {
            throw new BadRequestHttpException(
                "Domain parameter is required."
            );
        }

        try {
            $project = $this->service->create(
                new CreateProjectRequestDto(
                    domain: $domain,
                    scheme: $scheme,
                )
            );
        } catch (\Throwable $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

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
