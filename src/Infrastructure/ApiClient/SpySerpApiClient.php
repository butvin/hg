<?php

declare(strict_types=1);

namespace Infrastructure\ApiClient;

use Symfony\Component\HttpKernel\HttpClientKernel;

class SpySerpApiClient
{
    public function __construct(
        HttpClientKernel $httpClient
    ) {
    }

}
