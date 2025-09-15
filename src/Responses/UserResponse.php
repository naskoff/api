<?php

declare(strict_types=1);

namespace App\Responses;

use App\Entity\User;

final class UserResponse
{
    /**
     * @param User $user
     * @return array{id: string, email: string, roles: list<string>}
     */
    public static function fromEntity(User $user): array
    {
        return [
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'roles' => $user->getRoles(),
        ];
    }

    /**
     * @param User[] $users
     * @return array{id: string, email: string, roles: list<string>}[]
     */
    public static function fromCollection(array $users): array
    {
        return array_map(fn(User $user) => self::fromEntity($user), $users);
    }
}
