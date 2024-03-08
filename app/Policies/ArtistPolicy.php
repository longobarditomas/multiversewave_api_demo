<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Artist;

class ArtistPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function update(User $user, Artist $artist)
    {
        return $user->id === $artist->user_id;
    }
}
