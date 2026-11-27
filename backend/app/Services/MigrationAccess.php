<?php

namespace App\Services;

use App\Models\StaffUser;

final class MigrationAccess
{
    public static function allows(?StaffUser $user): bool
    {
        return $user !== null && $user->active
            && in_array(strtolower($user->email), config('phelon.migration_admins', []), true);
    }
}
