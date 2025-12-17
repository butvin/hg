<?php

declare(strict_types=1);

namespace UI\Http\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SpySerpController
{
    public function __construct(
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        return new JsonResponse(['message' => self::class], Response::HTTP_OK);
    }
}