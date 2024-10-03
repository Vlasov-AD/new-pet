<?php

declare(strict_types=1);

namespace App\User\DoctrineType;

use App\User\Collection\RoleCollection;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use InvalidArgumentException;

class RoleCollectionType extends Type
{
    public const ROLE_COLLECTION_TYPE_NAME = 'role_collection_type';

    /**
     * @inheritDoc
     */
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getStringTypeDeclarationSQL($column);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?RoleCollection
    {
        try {
            return (new RoleCollection())->add(json_decode($value, true));
        } catch (InvalidArgumentException $exception) {
            return null;
        }
    }

    /**
     * @param RoleCollection|null $value
     * @param AbstractPlatform $platform
     * @return string
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        return json_encode($value?->getAllValues() ?? []);
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return self::ROLE_COLLECTION_TYPE_NAME;
    }
}
