<?php

declare(strict_types=1);

namespace App\Common\ValueObject;

use JsonSerializable;

/**
 * @template TType
 */
abstract readonly class AbstractValueObject implements JsonSerializable
{
    public function __construct(mixed $value)
    {
        $this->validate($value);
    }


    abstract protected function validate(mixed $value): void;

    /**
     * @return TType
     */
    abstract public function getValue(): mixed;

    final public function setValue(mixed $value = null): static
    {
        return new static($value);
    }

    public function jsonSerialize(): mixed
    {
        return $this->getValue();
    }
}
