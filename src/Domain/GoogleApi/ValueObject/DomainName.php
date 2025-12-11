<?php

declare(strict_types=1);

namespace Domain\GoogleApi\ValueObject;

final readonly class DomainName
{
    private function __construct(private string $value) {}

    public static function fromString(string $value): self
    {
        $value = mb_strtolower(trim($value));
        if (!filter_var($value, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME) && !preg_match('/^[a-z0-9.-]+$/', $value)) {
            throw new \InvalidArgumentException(
                sprintf("Invalid domain name format: %s", $value)
            );
        }

        return new self($value);
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function equals(DomainName $other): bool
    {
        return $this->value === $other->value;
    }
}
