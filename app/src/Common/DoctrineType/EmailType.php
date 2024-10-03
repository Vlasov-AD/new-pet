<?php

declare(strict_types=1);

namespace App\Common\DoctrineType;

use App\Common\ValueObject\Email;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use InvalidArgumentException;

class EmailType extends Type
{
    public const EMAIL_TYPE_NAME = 'email_value_object_type';

    /**
     * @inheritDoc
     */
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getStringTypeDeclarationSQL($column);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?Email
    {
        try {
            return new Email($value);
        } catch (InvalidArgumentException $exception) {
            return null;
        }
    }

    /**
     * @param Email|string|null $value
     * @param AbstractPlatform $platform
     * @return string
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        if (is_string($value)) {
            $value = new Email($value);
        }
        return $value?->email;
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return self::EMAIL_TYPE_NAME;
    }
}
