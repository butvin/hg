<?php

declare(strict_types=1);

namespace UI\Http\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class SeRankingController
{
    public function __construct(
    ) {
    }

    public function index(): JsonResponse
    {
        return new JsonResponse(['message' => self::class], Response::HTTP_OK);
    }
}