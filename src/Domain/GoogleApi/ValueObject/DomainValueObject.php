<?php

declare(strict_types=1);

namespace Domain\GoogleApi\ValueObject;

use InvalidArgumentException;
use DomainException;

readonly class DomainValueObject
{
    private const int DOMAIN_NAME_LENGTH = 3;

    private function __construct(private string $value) {}

    public static function fromString(string $value): self
    {
        $value = mb_strtolower(trim($value));

        if (strlen($value) < self::DOMAIN_NAME_LENGTH) {
            throw new InvalidArgumentException(
                sprintf("Domain name '%s' must be more than 3 symbol", $value)
            );
        }

        if (!filter_var($value, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME) && !preg_match(
                '/^[a-z0-9.-]+$/',
                $value
            )) {
            throw new DomainException(
                sprintf("Invalid domain name format: %s", $value)
            );
        }

        return new self($value);
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function equals(DomainValueObject $other): bool
    {
        return $this->value === $other->value;
    }
}
