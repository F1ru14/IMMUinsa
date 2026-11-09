<?php

namespace App\Enums;

enum Role: string
{
    case Admin = 'admin';
    case Mentor = 'mentor';
    case Fasilitator = 'fasilitator';
    case Kader = 'kader';

    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Admin',
            self::Mentor => 'Mentor',
            self::Fasilitator => 'Fasilitator',
            self::Kader => 'Kader',
        };
    }

    /**
     * Roles allowed to register publicly.
     *
     * @return array<string>
     */
    public static function publicRoles(): array
    {
        return [
            self::Kader->value,
            self::Fasilitator->value,
            self::Mentor->value,
        ];
    }
}
