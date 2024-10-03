<?php

namespace App\Common\ValueObject;

use InvalidArgumentException;

/**
 * @template-extends AbstractValueObject<string>
 */
final readonly class Email extends AbstractValueObject
{
    public string $email;

    public function __construct(mixed $value)
    {
        parent::__construct($value);
        /** @var string $value */
        $this->email = $value;
    }

    protected function validate(mixed $value): void
    {
        if (!is_string($value) || filter_var($value, FILTER_VALIDATE_EMAIL) === false) {
            throw new InvalidArgumentException('Invalid email!');
        }
    }

    public function getValue(): string
    {
        return $this->email;
    }
}
