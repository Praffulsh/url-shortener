<?php

namespace App\Policies;

use App\Models\User;

class InvitationPolicy
{
    /**
     * Determine whether the user can create/send invitations.
     * SuperAdmin and Admin can invite; Member cannot.
     */
    public function create(User $user): bool
    {
        return $user->isSuperAdmin() || $user->isAdmin();
    }
}
