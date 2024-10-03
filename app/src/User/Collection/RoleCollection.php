<?php

declare(strict_types=1);

namespace App\User\Collection;

use App\User\Enum\Role;

/**
 * @template-extends EnumCollection<Role>
 */
class RoleCollection extends EnumCollection
{
    public function getEnumName(): string
    {
        return Role::class;
    }
}
