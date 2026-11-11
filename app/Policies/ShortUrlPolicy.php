<?php

namespace App\Policies;

use App\Models\ShortUrl;
use App\Models\User;

class ShortUrlPolicy
{
    /**
     * Determine whether the user can view any short URLs.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the specific short URL.
     */
    public function view(User $user, ShortUrl $shortUrl): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if ($user->isAdmin()) {
            return $shortUrl->company_id === $user->company_id;
        }

        if ($user->isMember()) {
            return $shortUrl->user_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can create short URLs.
     * SuperAdmin cannot create short URLs.
     * Admin and Member can create short URLs.
     */
    public function create(User $user): bool
    {
        return ! $user->isSuperAdmin();
    }
}
