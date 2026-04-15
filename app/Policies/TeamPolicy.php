<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Team;

class TeamPolicy
{
    // Check if the user can view the team
    public function view(User $user, Team $team)
    {
        // User can view the team if they are a member of it
        return $team->users->contains($user);
    }

    // Check if the user can update the team (change name, add members)
    public function update(User $user, Team $team)
    {
        // Only owner or user with admin role in this team
        if ($team->owner_id === $user->id) {
            return true;
        }
        return $team->users()->where('user_id', $user->id)->where('role', 'admin')->exists();
    }

    // Can delete team
    public function delete(User $user, Team $team)
    {
        // only owner
        return $team->owner_id === $user->id;
    }

    // Additionally: can add a member (reuse the same logic as update)
    public function addMember(User $user, Team $team)
    {
        return $this->update($user, $team);
    }
}
