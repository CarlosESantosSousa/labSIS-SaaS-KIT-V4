<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Spatie\Permission\Models\Role;

enum RoleType: string implements HasLabel
{
    case ADMIN = 'Admin';
    case USER = 'User';

    public function getLabel(): string
    {
        return match ($this) {
            self::ADMIN => 'Administrador',
            self::USER => 'Usuário',
        };
    }

    public static function ensureGlobalRoles(string $guard): void
    {
        // Apenas Admin deve existir de forma global
        Role::firstOrCreate([
            'name' => self::ADMIN->value,
            'guard_name' => $guard,
        ]);
    }

    public static function ensureUserRoleForTeam(int $teamId, string $guard): Role
    {
        return Role::firstOrCreate([
            'team_id' => $teamId,
            'name' => self::USER->value,
            'guard_name' => $guard,
        ]);
    }
}
