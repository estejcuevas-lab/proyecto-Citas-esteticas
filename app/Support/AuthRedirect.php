<?php

namespace App\Support;

use App\Models\User;

class AuthRedirect
{
    public static function routeFor(User $user): string
    {
        if ($user->needsProfileCompletion()) {
            return 'onboarding.profile.edit';
        }

        if ($user->needsBusinessApproval()) {
            return 'business-access.pending';
        }

        return 'dashboard';
    }
}
