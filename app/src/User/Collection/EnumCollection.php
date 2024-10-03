<?php

declare(strict_types=1);

namespace App\User\Collection;

use ArrayIterator;
use BackedEnum;
use Countable;
use InvalidArgumentException;
use IteratorAggregate;
use JsonSerializable;
use Traversable;

/**
 * @template TEnum of BackedEnum
 * @template-implements IteratorAggregate<int, TEnum>
 */
abstract class EnumCollection implements IteratorAggregate, Countable, JsonSerializable
{
    final public function __construct()
    {
    }

    /** @var BackedEnum $enums */
    private array $enums = [];

    /**
     * @param BackedEnum|BackedEnum|int|string $enum
     * @return static
     */
    final public function add(array|BackedEnum|int|string $enum): static
    {
        if (!is_array($enum)) {
            $enum = [$enum];
        }

        foreach ($enum as $item) {
            $this->addSingle($item);
        }

        return $this;
    }

    /**
     * @param BackedEnum $enum
     * @return static
     */
    final public function remove(BackedEnum $enum): static
    {
        $this->validateEnum($enum);

        foreach ($this->enums as $index => $item) {
            if ($item === $enum) {
                unset($this->enums[$index]);
            }
        }

        return $this;
    }

    private function addSingle(BackedEnum|int|string $enum): void
    {
        $this->validateEnum($enum);

        if (is_int($enum) || is_string($enum)) {
            /** @var class-string<TEnum> $enumName */
            $enumName = $this->getEnumName();
            $enum = $enumName::from($enum);
        }

        /** @var BackedEnum $enum */
        if (!$this->inArray($enum)) {
            $this->enums[] = $enum;
        }
    }

    public function count(): int
    {
        return count($this->enums);
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->enums);
    }

    /** @return BackedEnum */
    public function getAllValues(): array
    {
        $result = [];
        foreach ($this->enums as $enum) {
            /** @var value-of<TEnum> $value */
            $value = $enum->value;
            $result[] = $value;
        }
        return $result;
    }

    /** @return list<string> */
    public function getAllKeys(): array
    {
        $result = [];
        foreach ($this->enums as $enum) {
            $result[] = $enum->name;
        }
        return $result;
    }

    /**
     * @param BackedEnum $enum
     */
    public function inArray(BackedEnum $enum): bool
    {
        return in_array($enum, $this->enums, true);
    }

    /**
     * @param static<TEnum>|null $except
     * @return static
     */
    public function createCollectionForAllCases(?self $except = null): static
    {
        if ($except !== null && !$except instanceof $this) {
            throw new InvalidArgumentException("Некорректная коллекция!");
        }
        $collection  = new static();
        foreach ($this->getEnumName()::cases() as $enum) {
            if ($except === null || !$except->inArray($enum)) {
                $collection->add($enum);
            }
        }
        return $collection;
    }

    /** @return class-string<TEnum> */
    abstract public function getEnumName(): string;

    private function validateEnum(BackedEnum|int|string $enum): void
    {
        /** @var class-string<TEnum> $enumName */
        $enumName = $this->getEnumName();

        if ($enum instanceof $enumName) {
            return;
        }

        if ((is_int($enum) || is_string($enum)) && $enumName::tryFrom($enum) !== null) {
            return;
        }
        throw new InvalidArgumentException("Передан Enum некорректного типа!");
    }

    /**
     * @return BackedEnum
     */
    public function jsonSerialize(): array
    {
        return $this->getAllValues();
    }
}
