<?php

namespace App\Policies;

use App\User;
use App\Users;
use Illuminate\Auth\Access\HandlesAuthorization;

class UsersPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any users.
     *
     * @param  App\User  $user
     * @return bool
     */
    public function viewAny(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can view the users.
     *
     * @param  App\User  $user
     * @param  App\Users  $users
     * @return bool
     */
    public function view(User $user, Users $users)
    {
        return false;
    }

    /**
     * Determine whether the user can create a users.
     *
     * @param  App\User  $user
     * @return bool
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the users.
     *
     * @param  App\User  $user
     * @param  App\Users  $users
     * @return bool
     */
    public function update(User $user, Users $users)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the users.
     *
     * @param  App\User  $user
     * @param  App\Users  $users
     * @return bool
     */
    public function delete(User $user, Users $users)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the users.
     *
     * @param  App\User  $user
     * @param  App\Users  $users
     * @return bool
     */
    public function restore(User $user, Users $users)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the users.
     *
     * @param  App\User  $user
     * @param  App\Users  $users
     * @return bool
     */
    public function forceDelete(User $user, Users $users)
    {
        return false;
    }
}
