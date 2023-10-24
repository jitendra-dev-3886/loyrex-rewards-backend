<?php

namespace App\Policies;

use App\User;
use App\PasswordReset;
use Illuminate\Auth\Access\HandlesAuthorization;

class PasswordResetsPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any passwordResets.
     *
     * @param  App\User  $user
     * @return bool
     */
    public function viewAny(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can view the passwordResets.
     *
     * @param  App\User  $user
     * @param  App\PasswordResets  $passwordResets
     * @return bool
     */
    public function view(User $user, PasswordReset $passwordResets)
    {
        return false;
    }

    /**
     * Determine whether the user can create a passwordResets.
     *
     * @param  App\User  $user
     * @return bool
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the passwordResets.
     *
     * @param  App\User  $user
     * @param  App\PasswordResets  $passwordResets
     * @return bool
     */
    public function update(User $user, PasswordReset $passwordResets)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the passwordResets.
     *
     * @param  App\User  $user
     * @param  App\PasswordResets  $passwordResets
     * @return bool
     */
    public function delete(User $user, PasswordReset $passwordResets)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the passwordResets.
     *
     * @param  App\User  $user
     * @param  App\PasswordResets  $passwordResets
     * @return bool
     */
    public function restore(User $user, PasswordReset $passwordResets)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the passwordResets.
     *
     * @param  App\User  $user
     * @param  App\PasswordResets  $passwordResets
     * @return bool
     */
    public function forceDelete(User $user, PasswordReset $passwordResets)
    {
        return false;
    }
}
