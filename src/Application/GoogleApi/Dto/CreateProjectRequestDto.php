<?php

namespace Application\GoogleApi\Dto;

final readonly class CreateProjectRequestDto
{
    public function __construct(
        private string $domain,
        private string $scheme,
    ) {
    }

    public function getScheme(): string
    {
        return $this->scheme;
    }

    public function getDomain(): string
    {
        return $this->domain;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['domain'],
            $data['scheme'],
        );
    }

}